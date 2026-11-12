<?php

namespace App\Plugins\VtuNaijaPriceSync\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\VtuNaijaPriceSync\Services\ApiClient;
use App\Plugins\VtuNaijaPriceSync\Services\Installer;
use App\Plugins\VtuNaijaPriceSync\Services\MarkupCalculator;
use App\Plugins\VtuNaijaPriceSync\Services\ProviderSetupService;
use App\Plugins\VtuNaijaPriceSync\Services\SyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VtuNaijaPriceSyncController extends Controller
{
    private const PRICE_CHANGE_NOTICE_TITLE = 'VTUNAIJA price changes detected';

    private SyncService $sync;

    public function __construct()
    {
        Installer::install();
        $this->sync = new SyncService();
    }

    public function index(Request $request)
    {
        $productType = $request->query('type', 'data');
        $adapters = $this->sync->adapters();

        if (!isset($adapters[$productType])) {
            $productType = 'data';
        }

        return view('plugins.vtunaijapricesync::index', [
            'adapters' => $adapters,
            'productType' => $productType,
            'rows' => [],
            'payload' => [],
            'apiReady' => (new ApiClient())->hasConfig(),
            'setupStatus' => (new ProviderSetupService())->status(),
            'rules' => (new MarkupCalculator())->rules(),
            'runs' => DB::table('vtunaija_price_sync_runs')->latest('id')->limit(10)->get(),
        ]);
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'product_type' => 'required|in:data,cabletv,electricity',
        ]);

        $result = $this->sync->preview($validated['product_type']);

        if (!$result['ok']) {
            return redirect()
                ->route('admin.vtunaija-price-sync.index', ['type' => $validated['product_type']])
                ->with('error', $result['message']);
        }

        return view('plugins.vtunaijapricesync::index', [
            'adapters' => $this->sync->adapters(),
            'productType' => $validated['product_type'],
            'rows' => $result['rows'],
            'payload' => $result['payload'],
            'previewRunId' => $result['run_id'],
            'apiReady' => (new ApiClient())->hasConfig(),
            'setupStatus' => (new ProviderSetupService())->status(),
            'rules' => (new MarkupCalculator())->rules(),
            'runs' => DB::table('vtunaija_price_sync_runs')->latest('id')->limit(10)->get(),
        ]);
    }

    public function setup()
    {
        return view('plugins.vtunaijapricesync::setup', [
            'setupStatus' => (new ProviderSetupService())->status(),
        ]);
    }

    public function storeSetup(Request $request)
    {
        $validated = $request->validate([
            'api_token' => 'required|string|max:255',
            'replace_existing' => 'nullable|boolean',
        ]);

        $summary = (new ProviderSetupService())->install(
            trim($validated['api_token']),
            $request->boolean('replace_existing', true)
        );

        return redirect()
            ->route('admin.vtunaija-price-sync.index')
            ->with('success', 'VTUNAIJA setup completed. '
                . "{$summary['categories_created']} categories, "
                . "{$summary['services_created']} services, "
                . "{$summary['providers_created']} providers, "
                . "{$summary['configs_created']} configs created, "
                . "{$summary['configs_updated']} configs updated.");
    }

    public function apply(Request $request)
    {
        $validated = $request->validate([
            'product_type' => 'required|in:data,cabletv,electricity',
            'payload' => 'required|string',
            'selected' => 'array',
            'selected.*' => 'string',
        ]);

        $rows = json_decode($validated['payload'], true);
        if (!is_array($rows)) {
            return redirect()
                ->route('admin.vtunaija-price-sync.index', ['type' => $validated['product_type']])
                ->with('error', 'The selected preview could not be read. Fetch the latest plans again.');
        }

        $result = $this->sync->apply(
            $validated['product_type'],
            $rows,
            $request->input('selected', [])
        );

        if (($result['created'] + $result['updated']) > 0) {
            DB::table('notifications')
                ->where('title', self::PRICE_CHANGE_NOTICE_TITLE)
                ->update([
                    'status' => 'inactive',
                    'message' => 'VTUNAIJA price changes were reviewed and applied.',
                    'date' => now(),
                ]);
        }

        return redirect()
            ->route('admin.vtunaija-price-sync.runs.show', $result['run_id'])
            ->with('success', "Sync applied. Created: {$result['created']}, Updated: {$result['updated']}, Skipped: {$result['skipped']}.");
    }

    public function storeRule(Request $request)
    {
        Installer::install();

        $validator = Validator::make($request->all(), [
            'return_product_type' => 'nullable|in:data,cabletv,electricity',
            'product_type' => 'nullable|in:data,cabletv,electricity',
            'scope_type' => 'required|in:global,product_type,network,type,product',
            'scope_key' => 'nullable|string|max:255',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'markup_type' => 'required|in:flat,percent',
            'markup_value' => 'required|numeric|min:0',
            'customer_markup_value' => 'nullable|numeric|min:0',
            'reseller_markup_value' => 'nullable|numeric|min:0',
            'agent_markup_value' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->ruleRedirect($request)
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();

        if (($validated['product_type'] ?? null) === 'electricity') {
            $validated['markup_type'] = 'percent';
        }

        $priceMin = $validated['price_min'] ?? null;
        $priceMax = $validated['price_max'] ?? null;

        if ($priceMin !== null && $priceMax !== null && (float) $priceMin > (float) $priceMax) {
            return $this->ruleRedirect($request)
                ->withInput()
                ->with('error', 'Minimum price cannot be greater than maximum price.');
        }

        $scopeType = $validated['scope_type'];
        $productType = $scopeType === 'global' ? null : ($validated['product_type'] ?? null);

        if ($scopeType !== 'global' && !$productType) {
            return $this->ruleRedirect($request)
                ->withInput()
                ->with('error', 'Product type is required unless the scope is global.');
        }

        $scopeKey = match ($scopeType) {
            'global' => null,
            'product_type' => $productType,
            default => strtolower(trim((string) ($validated['scope_key'] ?? ''))),
        };

        if (!in_array($scopeType, ['global', 'product_type'], true) && !$scopeKey) {
            return $this->ruleRedirect($request)
                ->withInput()
                ->with('error', 'Scope key is required unless the scope is global.');
        }

        try {
            DB::table('vtunaija_price_sync_markup_rules')->insert([
                'product_type' => $productType,
                'scope_type' => $scopeType,
                'scope_key' => $scopeKey,
                'price_min' => $priceMin,
                'price_max' => $priceMax,
                'markup_type' => $validated['markup_type'],
                'markup_value' => $validated['markup_value'],
                'customer_markup_value' => $validated['customer_markup_value'] ?? null,
                'reseller_markup_value' => $validated['reseller_markup_value'] ?? null,
                'agent_markup_value' => $validated['agent_markup_value'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('VTUNAIJA markup rule could not be saved.', [
                'error' => $e->getMessage(),
                'payload' => $request->except(['_token']),
            ]);

            return $this->ruleRedirect($request)
                ->withInput()
                ->with('error', 'The markup rule could not be saved. The sync tables were refreshed; please try again.');
        }

        return $this->ruleRedirect($request)->with('success', 'Markup rule added.');
    }

    public function deleteRule(Request $request, int $id)
    {
        DB::table('vtunaija_price_sync_markup_rules')->where('id', $id)->delete();

        return $this->ruleRedirect($request)->with('success', 'Markup rule deleted.');
    }

    private function ruleRedirect(Request $request)
    {
        $type = $request->input('return_product_type') ?: $request->input('product_type') ?: 'data';

        if (!in_array($type, ['data', 'cabletv', 'electricity'], true)) {
            $type = 'data';
        }

        return redirect()->route('admin.vtunaija-price-sync.index', ['type' => $type]);
    }

    public function run(int $id)
    {
        $run = DB::table('vtunaija_price_sync_runs')->where('id', $id)->first();
        abort_unless($run, 404);

        return view('plugins.vtunaijapricesync::run', [
            'run' => $run,
            'items' => DB::table('vtunaija_price_sync_run_items')->where('run_id', $id)->orderBy('id')->get(),
        ]);
    }
}
