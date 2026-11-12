<?php

namespace App\Plugins\VtuNaijaPriceSync\Services\Adapters;

interface CatalogAdapter
{
    public function productType(): string;

    public function label(): string;

    public function endpoint(): string;

    public function responseKey(): string;

    public function normalize(array $item): array;
}
