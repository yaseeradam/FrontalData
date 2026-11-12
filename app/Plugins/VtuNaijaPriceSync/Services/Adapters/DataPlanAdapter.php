<?php

namespace App\Plugins\VtuNaijaPriceSync\Services\Adapters;

class DataPlanAdapter implements CatalogAdapter
{
    public function productType(): string
    {
        return 'data';
    }

    public function label(): string
    {
        return 'Data Plans';
    }

    public function endpoint(): string
    {
        return '/api/listdataplans/';
    }

    public function responseKey(): string
    {
        return 'dataplans';
    }

    public function normalize(array $item): array
    {
        return [
            'product_type' => $this->productType(),
            'external_id' => (string) ($item['data_plan_id'] ?? ''),
            'service_name' => strtoupper(trim((string) ($item['the_network_name'] ?? ''))),
            'category_id' => 3,
            'product_name' => trim((string) ($item['size'] ?? '')),
            'subcategory' => trim((string) ($item['the_datatype_name'] ?? '')),
            'validity' => (string) ($item['duration'] ?? ''),
            'base_price' => (float) ($item['price_for_premiumuser'] ?? 0),
            'price_mode' => 'fixed',
            'source_status' => (string) ($item['status'] ?? ''),
            'source' => $item,
        ];
    }
}
