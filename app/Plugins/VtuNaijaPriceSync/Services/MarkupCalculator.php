<?php

namespace App\Plugins\VtuNaijaPriceSync\Services;

use Illuminate\Support\Facades\DB;

class MarkupCalculator
{
    public function prices(array $item): array
    {
        $base = (float) $item['base_price'];
        $rule = $this->resolveRule($item);
        $mode = $item['price_mode'] ?? 'fixed';

        return [
            'buying_price' => round($base, 2),
            'customer_price' => round($this->apply($base, $rule, $mode, 'customer'), 2),
            'reseller_price' => round($this->apply($base, $rule, $mode, 'reseller'), 2),
            'agent_price' => round($this->apply($base, $rule, $mode, 'agent'), 2),
            'rule' => $rule,
        ];
    }

    public function rules()
    {
        return DB::table('vtunaija_price_sync_markup_rules')
            ->orderByRaw("case scope_type when 'product' then 1 when 'network' then 2 when 'type' then 3 when 'product_type' then 4 else 5 end")
            ->orderBy('product_type')
            ->orderBy('price_min')
            ->orderBy('price_max')
            ->get();
    }

    private function resolveRule(array $item): ?object
    {
        $productType = $item['product_type'];
        $base = (float) $item['base_price'];
        $candidates = [
            ['product', (string) $item['external_id'], $productType],
            ['network', $this->normalize($item['service_name'] ?? ''), $productType],
            ['type', $this->normalize($item['subcategory'] ?? ''), $productType],
            ['product_type', $productType, $productType],
            ['global', null, null],
        ];

        foreach ($candidates as [$scopeType, $scopeKey, $type]) {
            $query = DB::table('vtunaija_price_sync_markup_rules')
                ->where('scope_type', $scopeType);

            if ($type === null) {
                $query->whereNull('product_type');
            } else {
                $query->where('product_type', $type);
            }

            if ($scopeKey === null) {
                $query->whereNull('scope_key');
            } else {
                $query->where('scope_key', $scopeKey);
            }

            $rule = $query
                ->where(function ($query) use ($base) {
                    $query->whereNull('price_min')
                        ->orWhere('price_min', '<=', $base);
                })
                ->where(function ($query) use ($base) {
                    $query->whereNull('price_max')
                        ->orWhere('price_max', '>=', $base);
                })
                ->orderByRaw('case when price_min is null and price_max is null then 1 else 0 end')
                ->latest('id')
                ->first();
            if ($rule) {
                return $rule;
            }
        }

        return null;
    }

    private function apply(float $base, ?object $rule, string $mode, string $role): float
    {
        if (!$rule) {
            return $base;
        }

        $roleColumn = "{$role}_markup_value";
        $value = $rule->{$roleColumn} ?? null;

        if ($value === null || $value === '') {
            $value = $rule->markup_value;
        }

        $value = (float) $value;

        if ($mode === 'percent') {
            return $base + $value;
        }

        if ($rule->markup_type === 'percent') {
            return $base + (($base * $value) / 100);
        }

        return $base + $value;
    }

    private function normalize(string $value): string
    {
        return strtolower(trim($value));
    }
}
