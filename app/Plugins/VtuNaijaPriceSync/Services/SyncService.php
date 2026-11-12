<?php

namespace App\Plugins\VtuNaijaPriceSync\Services;

use App\Models\Product;
use App\Models\Service;
use App\Plugins\VtuNaijaPriceSync\Services\Adapters\CableTvPlanAdapter;
use App\Plugins\VtuNaijaPriceSync\Services\Adapters\CatalogAdapter;
use App\Plugins\VtuNaijaPriceSync\Services\Adapters\DataPlanAdapter;
use App\Plugins\VtuNaijaPriceSync\Services\Adapters\ElectricityPlanAdapter;
use Illuminate\Support\Facades\DB;

class SyncService
{
    public function __construct(
        private readonly ApiClient $client = new ApiClient(),
        private readonly MarkupCalculator $markup = new MarkupCalculator()
    ) {
        Installer::install();
    }

    public function adapters(): array
    {
        return [
            'data' => new DataPlanAdapter(),
            'cabletv' => new CableTvPlanAdapter(),
            'electricity' => new ElectricityPlanAdapter(),
        ];
    }

    public function adapter(string $productType): CatalogAdapter
    {
        $adapters = $this->adapters();

        if (!isset($adapters[$productType])) {
            abort(404);
        }

        return $adapters[$productType];
    }

    public function preview(string $productType): array
    {
        $result = $this->buildRows($productType);

        if (!$result['ok']) {
            return [
                'ok' => false,
                'message' => $result['message'],
                'rows' => [],
                'payload' => [],
            ];
        }

        $rows = $result['rows'];
        $runId = $this->recordRun($productType, 'previewed', $rows);

        return [
            'ok' => true,
            'message' => null,
            'run_id' => $runId,
            'rows' => $rows,
            'payload' => $rows,
        ];
    }

    public function checkForChanges(array $productTypes = []): array
    {
        $adapters = $this->adapters();
        $types = $productTypes
            ? array_values(array_intersect($productTypes, array_keys($adapters)))
            : array_keys($adapters);

        if (!$types) {
            $types = array_keys($adapters);
        }

        $summary = [
            'ok' => true,
            'runs' => [],
            'checked_types' => 0,
            'fetched' => 0,
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'changes' => 0,
            'failed' => 0,
            'messages' => [],
        ];

        foreach ($types as $productType) {
            $result = $this->buildRows($productType);

            if (!$result['ok']) {
                $runId = DB::table('vtunaija_price_sync_runs')->insertGetId([
                    'product_type' => $productType,
                    'status' => 'failed',
                    'fetched_count' => 0,
                    'message' => $result['message'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $summary['ok'] = false;
                $summary['failed']++;
                $summary['messages'][] = "{$productType}: {$result['message']}";
                $summary['runs'][] = [
                    'id' => $runId,
                    'product_type' => $productType,
                    'status' => 'failed',
                    'message' => $result['message'],
                    'created' => 0,
                    'updated' => 0,
                    'skipped' => 0,
                ];

                continue;
            }

            $rows = $result['rows'];
            $counts = $this->rowCounts($rows);
            $changes = $counts['created'] + $counts['updated'];
            $status = $changes > 0 ? 'changes_found' : 'no_changes';
            $runId = $this->recordRun($productType, $status, $rows, $counts);

            $summary['checked_types']++;
            $summary['fetched'] += count($rows);
            $summary['created'] += $counts['created'];
            $summary['updated'] += $counts['updated'];
            $summary['skipped'] += $counts['skipped'];
            $summary['changes'] += $changes;
            $summary['runs'][] = [
                'id' => $runId,
                'product_type' => $productType,
                'status' => $status,
                'message' => null,
                'created' => $counts['created'],
                'updated' => $counts['updated'],
                'skipped' => $counts['skipped'],
            ];
        }

        return $summary;
    }

    public function apply(string $productType, array $rows, array $selected): array
    {
        $created = 0;
        $updated = 0;
        $skipped = 0;

        $runId = DB::table('vtunaija_price_sync_runs')->insertGetId([
            'product_type' => $productType,
            'status' => 'applied',
            'fetched_count' => count($rows),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($rows as $index => $row) {
            if (!in_array((string) $index, $selected, true)) {
                continue;
            }

            if (!in_array($row['action'], ['create', 'update'], true)) {
                $skipped++;
                $this->recordItem($runId, $productType, $row, 'skipped', $row['message'] ?: 'No applicable change.');
                continue;
            }

            if ($row['action'] === 'create') {
                $product = $this->createProduct($row['item'], $row['proposed']);
                $created++;
            } else {
                $product = Product::find($row['product']['id'] ?? null);
                if (!$product) {
                    $skipped++;
                    $this->recordItem($runId, $productType, $row, 'skipped', 'Matched product no longer exists.');
                    continue;
                }

                $this->updateProduct($product, $row['item'], $row['proposed']);
                $updated++;
            }

            $this->mapProduct($row['item'], $product);
            $row['product'] = $this->productSnapshot($product);
            $this->recordItem($runId, $productType, $row, $row['action'] === 'create' ? 'created' : 'updated');
        }

        DB::table('vtunaija_price_sync_runs')->where('id', $runId)->update([
            'created_count' => $created,
            'updated_count' => $updated,
            'skipped_count' => $skipped,
            'updated_at' => now(),
        ]);

        return [
            'run_id' => $runId,
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
        ];
    }

    private function previewRow(array $item): array
    {
        $message = null;
        $product = null;

        if ($item['external_id'] === '' || $item['product_name'] === '' || $item['base_price'] <= 0) {
            return $this->row($item, null, 'needs_review', null, 'Missing required plan ID, name, or base price.');
        }

        if (strtolower($item['source_status']) !== 'on') {
            return $this->row($item, null, 'skipped', null, 'Plan is not active on VTUNAIJA.');
        }

        $service = $this->findService($item);

        if (!$service && $item['product_type'] !== 'electricity') {
            return $this->row($item, null, 'needs_review', null, 'No matching local service was found.');
        }

        $product = $this->mappedProduct($item) ?: $this->matchedProduct($item, $service);
        $proposed = $this->proposedProduct($item, $service);

        if (!$product) {
            return $this->row($item, null, 'create', $proposed, $message);
        }

        $snapshot = $this->productSnapshot($product);
        $action = $this->hasChanges($snapshot, $proposed) ? 'update' : 'unchanged';

        return $this->row($item, $snapshot, $action, $proposed, $message);
    }

    private function buildRows(string $productType): array
    {
        $adapter = $this->adapter($productType);
        $response = $this->client->fetch($adapter->endpoint());

        if (!$response['ok']) {
            return [
                'ok' => false,
                'message' => $response['message'],
                'rows' => [],
            ];
        }

        $items = $response['data'][$adapter->responseKey()] ?? [];
        $rows = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $rows[] = $this->previewRow($adapter->normalize($item));
        }

        return [
            'ok' => true,
            'message' => null,
            'rows' => $rows,
        ];
    }

    private function recordRun(string $productType, string $status, array $rows, ?array $counts = null, ?string $message = null): int
    {
        $counts ??= $this->rowCounts($rows);

        $runId = DB::table('vtunaija_price_sync_runs')->insertGetId([
            'product_type' => $productType,
            'status' => $status,
            'fetched_count' => count($rows),
            'created_count' => $counts['created'],
            'updated_count' => $counts['updated'],
            'skipped_count' => $counts['skipped'],
            'message' => $message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($rows as $row) {
            DB::table('vtunaija_price_sync_run_items')->insert([
                'run_id' => $runId,
                'product_type' => $productType,
                'external_id' => $row['item']['external_id'] ?: null,
                'product_id' => $row['product']['id'] ?? null,
                'action' => $row['action'],
                'status' => $this->runItemStatus($status, $row['action']),
                'before_snapshot' => isset($row['product']) ? json_encode($row['product']) : null,
                'after_snapshot' => json_encode($row['proposed']),
                'message' => $row['message'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $runId;
    }

    private function rowCounts(array $rows): array
    {
        $counts = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
        ];

        foreach ($rows as $row) {
            if (($row['action'] ?? null) === 'create') {
                $counts['created']++;
                continue;
            }

            if (($row['action'] ?? null) === 'update') {
                $counts['updated']++;
                continue;
            }

            if (in_array($row['action'] ?? null, ['skipped', 'needs_review'], true)) {
                $counts['skipped']++;
            }
        }

        return $counts;
    }

    private function runItemStatus(string $runStatus, string $action): string
    {
        if ($runStatus === 'changes_found') {
            return in_array($action, ['create', 'update'], true) ? 'pending_review' : 'checked';
        }

        if ($runStatus === 'no_changes') {
            return 'checked';
        }

        return $runStatus;
    }

    private function row(array $item, ?array $product, string $action, ?array $proposed, ?string $message): array
    {
        return [
            'item' => $item,
            'product' => $product,
            'action' => $action,
            'proposed' => $proposed ?: $this->proposedProduct($item, $this->findService($item)),
            'message' => $message,
        ];
    }

    private function proposedProduct(array $item, ?Service $service): array
    {
        $prices = $this->markup->prices($item);

        return [
            'name' => $item['product_name'],
            'service_id' => $service?->id,
            'plan_id' => $item['external_id'],
            'subcategory' => $item['subcategory'],
            'validity' => $item['validity'],
            'buying_price' => $prices['buying_price'],
            'customer_price' => $prices['customer_price'],
            'reseller_price' => $prices['reseller_price'],
            'agent_price' => $prices['agent_price'],
            'markup_rule' => $prices['rule'] ? [
                'scope_type' => $prices['rule']->scope_type,
                'scope_key' => $prices['rule']->scope_key,
                'price_min' => $prices['rule']->price_min,
                'price_max' => $prices['rule']->price_max,
                'markup_type' => $prices['rule']->markup_type,
                'markup_value' => $prices['rule']->markup_value,
            ] : null,
        ];
    }

    private function createProduct(array $item, array $proposed): Product
    {
        $service = $this->findService($item);

        if (!$service && $item['product_type'] === 'electricity') {
            $service = Service::create([
                'name' => $item['service_name'],
                'category_id' => $item['category_id'],
                'network_id' => (int) $item['external_id'],
            ]);
        }

        $proposed['service_id'] = $service?->id;
        unset($proposed['markup_rule']);

        return Product::create($proposed);
    }

    private function updateProduct(Product $product, array $item, array $proposed): void
    {
        if ($item['product_type'] === 'electricity' && $product->service) {
            $product->service->update(['network_id' => (int) $item['external_id']]);
        }

        unset($proposed['markup_rule']);
        $product->update($proposed);
        $product->refresh();
    }

    private function mapProduct(array $item, Product $product): void
    {
        DB::table('vtunaija_price_sync_mappings')->updateOrInsert(
            [
                'product_type' => $item['product_type'],
                'external_id' => (string) $item['external_id'],
            ],
            [
                'product_id' => $product->id,
                'source_snapshot' => json_encode($item['source']),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    private function mappedProduct(array $item): ?Product
    {
        $mapping = DB::table('vtunaija_price_sync_mappings')
            ->where('product_type', $item['product_type'])
            ->where('external_id', (string) $item['external_id'])
            ->first();

        return $mapping ? Product::find($mapping->product_id) : null;
    }

    private function matchedProduct(array $item, ?Service $service): ?Product
    {
        if (!$service) {
            return null;
        }

        $query = Product::where('service_id', $service->id)
            ->where('plan_id', $item['external_id']);

        $product = $query->first();
        if ($product) {
            return $product;
        }

        $query = Product::where('service_id', $service->id)
            ->whereRaw('LOWER(name) = ?', [strtolower($item['product_name'])]);

        if ($item['subcategory']) {
            $query->whereRaw('LOWER(subcategory) = ?', [strtolower($item['subcategory'])]);
        }

        return $query->first();
    }

    private function findService(array $item): ?Service
    {
        return Service::where('category_id', $item['category_id'])
            ->whereRaw('LOWER(name) = ?', [strtolower($item['service_name'])])
            ->first();
    }

    private function productSnapshot(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'service_id' => $product->service_id,
            'service_name' => $product->service->name ?? null,
            'plan_id' => (string) $product->plan_id,
            'subcategory' => $product->subcategory,
            'validity' => $product->validity,
            'buying_price' => (float) $product->buying_price,
            'customer_price' => (float) $product->customer_price,
            'reseller_price' => (float) $product->reseller_price,
            'agent_price' => (float) $product->agent_price,
        ];
    }

    private function hasChanges(array $product, array $proposed): bool
    {
        foreach (['name', 'plan_id', 'subcategory', 'validity'] as $field) {
            if ((string) ($product[$field] ?? '') !== (string) ($proposed[$field] ?? '')) {
                return true;
            }
        }

        foreach (['buying_price', 'customer_price', 'reseller_price', 'agent_price'] as $field) {
            if (round((float) ($product[$field] ?? 0), 2) !== round((float) ($proposed[$field] ?? 0), 2)) {
                return true;
            }
        }

        return false;
    }

    private function recordItem(int $runId, string $productType, array $row, string $status, ?string $message = null): void
    {
        DB::table('vtunaija_price_sync_run_items')->insert([
            'run_id' => $runId,
            'product_type' => $productType,
            'external_id' => $row['item']['external_id'] ?: null,
            'product_id' => $row['product']['id'] ?? null,
            'action' => $row['action'],
            'status' => $status,
            'before_snapshot' => isset($row['product']) ? json_encode($row['product']) : null,
            'after_snapshot' => json_encode($row['proposed']),
            'message' => $message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
