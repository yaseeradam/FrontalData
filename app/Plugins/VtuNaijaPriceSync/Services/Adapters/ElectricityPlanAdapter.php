<?php

namespace App\Plugins\VtuNaijaPriceSync\Services\Adapters;

class ElectricityPlanAdapter implements CatalogAdapter
{
    public function productType(): string
    {
        return 'electricity';
    }

    public function label(): string
    {
        return 'Electricity Plan IDs';
    }

    public function endpoint(): string
    {
        return '/api/listelectricity/';
    }

    public function responseKey(): string
    {
        return 'electricityplanids';
    }

    public function normalize(array $item): array
    {
        $name = $this->localDiscoName((string) ($item['the_electricty_name'] ?? ''));

        return [
            'product_type' => $this->productType(),
            'external_id' => (string) ($item['electricity_plan_id'] ?? ''),
            'service_name' => $name,
            'category_id' => 8,
            'product_name' => $name,
            'subcategory' => null,
            'validity' => null,
            'base_price' => $this->percent($item['commission_for_premiumuser'] ?? 0),
            'price_mode' => 'percent',
            'source_status' => 'On',
            'source' => $item,
        ];
    }

    private function percent(string|float|int $value): float
    {
        return (float) str_replace('%', '', (string) $value);
    }

    private function localDiscoName(string $name): string
    {
        $normalized = strtolower($name);

        return match (true) {
            str_contains($normalized, 'ikeja') => 'ikeja electricity',
            str_contains($normalized, 'eko') => 'eko electricity',
            str_contains($normalized, 'kano') => 'kano electricity',
            str_contains($normalized, 'port harcourt') => 'port harcourt electricity',
            str_contains($normalized, 'jos') => 'jos electricity',
            str_contains($normalized, 'ibadan') => 'ibadan electricity',
            str_contains($normalized, 'abuja') => 'abuja electricity',
            str_contains($normalized, 'enugu') => 'enugu electricity',
            str_contains($normalized, 'benin') => 'benin electricity',
            str_contains($normalized, 'kaduna') => 'kaduna electricity',
            default => trim(preg_replace('/\s+/', ' ', str_replace('distribution company', '', $normalized))),
        };
    }
}
