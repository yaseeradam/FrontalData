<?php

namespace App\Plugins\VtuNaijaPriceSync\Services\Adapters;

class CableTvPlanAdapter implements CatalogAdapter
{
    public function productType(): string
    {
        return 'cabletv';
    }

    public function label(): string
    {
        return 'Cable TV Plans';
    }

    public function endpoint(): string
    {
        return '/api/listcabletvplans/';
    }

    public function responseKey(): string
    {
        return 'cabletvplans';
    }

    public function normalize(array $item): array
    {
        return [
            'product_type' => $this->productType(),
            'external_id' => (string) ($item['cabletv_plan_id'] ?? ''),
            'service_name' => strtoupper(trim((string) ($item['the_cabletv_name'] ?? ''))),
            'category_id' => 5,
            'product_name' => trim((string) ($item['size'] ?? '')),
            'subcategory' => trim((string) ($item['the_cabletv_type_name'] ?? '')),
            'validity' => (string) ($item['duration'] ?? ''),
            'base_price' => (float) ($item['price_for_premiumuser'] ?? 0),
            'price_mode' => 'fixed',
            'source_status' => (string) ($item['status'] ?? ''),
            'source' => $item,
        ];
    }
}
