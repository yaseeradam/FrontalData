<?php

namespace App\Plugins\VtuNaijaPriceSync\Services;

use App\Models\ApiConfig;
use App\Models\ApiProvider;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ProviderSetupService
{
    public function status(): array
    {
        $providerIds = ApiProvider::query()
            ->where('api_endpoint', 'like', '%vtunaija.com.ng%')
            ->pluck('id');

        return [
            'providers' => $providerIds->count(),
            'configs' => $providerIds->isEmpty()
                ? 0
                : ApiConfig::whereIn('provider_id', $providerIds)->where('status', 'enabled')->count(),
            'services' => Service::query()->count(),
            'categories' => Category::query()->count(),
        ];
    }

    public function install(string $apiToken, bool $replaceExisting = true): array
    {
        $summary = [
            'categories_created' => 0,
            'services_created' => 0,
            'providers_created' => 0,
            'configs_created' => 0,
            'configs_updated' => 0,
        ];

        DB::transaction(function () use ($apiToken, $replaceExisting, &$summary) {
            $categories = $this->seedCategories($summary);
            $services = $this->seedServices($categories, $summary);
            $providers = $this->seedProviders($categories, $summary);
            $this->seedConfigs($apiToken, $services, $providers, $replaceExisting, $summary);
        });

        return $summary;
    }

    private function seedCategories(array &$summary): array
    {
        $categories = [];

        foreach ($this->categoryNames() as $name) {
            $category = Category::firstOrCreate(['name' => $name]);

            if ($category->wasRecentlyCreated) {
                $summary['categories_created']++;
            }

            $categories[$name] = $category;
        }

        return $categories;
    }

    private function seedServices(array $categories, array &$summary): array
    {
        $services = [];

        foreach ($this->servicePresets() as $categoryName => $items) {
            foreach ($items as $item) {
                $service = Service::firstOrCreate(
                    [
                        'name' => $item['name'],
                        'category_id' => $categories[$categoryName]->id,
                    ],
                    [
                        'network_id' => $item['network_id'],
                    ]
                );

                if ($service->wasRecentlyCreated) {
                    $summary['services_created']++;
                } elseif ((string) $service->network_id !== (string) $item['network_id']) {
                    $service->update(['network_id' => $item['network_id']]);
                }

                $services[$categoryName][$item['name']] = $service;
            }
        }

        return $services;
    }

    private function seedProviders(array $categories, array &$summary): array
    {
        $providers = [];

        foreach ($this->providerPresets() as $key => $preset) {
            $provider = ApiProvider::firstOrCreate(
                [
                    'api_endpoint' => $preset['api_endpoint'],
                    'category_id' => $categories[$preset['category']]->id,
                ],
                [
                    'name' => 'VTUNaija',
                    'identifier' => $preset['identifier'],
                ]
            );

            $provider->update([
                'name' => 'VTUNaija',
                'identifier' => $preset['identifier'],
            ]);

            if ($provider->wasRecentlyCreated) {
                $summary['providers_created']++;
            }

            $providers[$key] = $provider;
        }

        return $providers;
    }

    private function seedConfigs(string $apiToken, array $services, array $providers, bool $replaceExisting, array &$summary): void
    {
        foreach ($this->providerPresets() as $key => $preset) {
            foreach ($preset['services'] as $serviceName) {
                $service = $services[$preset['category']][$serviceName] ?? null;

                if (!$service) {
                    continue;
                }

                if ($replaceExisting) {
                    $config = ApiConfig::where('service_id', $service->id)->first();
                    $created = !$config;

                    $config = $config ?: new ApiConfig(['service_id' => $service->id]);
                    $config->fill([
                        'provider_id' => $providers[$key]->id,
                        'api_token' => $apiToken,
                        'status' => 'enabled',
                        'optional' => null,
                    ]);
                    $config->save();

                    $summary[$created ? 'configs_created' : 'configs_updated']++;
                    continue;
                }

                $config = ApiConfig::firstOrNew([
                    'service_id' => $service->id,
                    'provider_id' => $providers[$key]->id,
                ]);
                $created = !$config->exists;

                $config->fill([
                    'api_token' => $apiToken,
                    'status' => 'enabled',
                    'optional' => null,
                ]);
                $config->save();

                $summary[$created ? 'configs_created' : 'configs_updated']++;
            }
        }
    }

    private function categoryNames(): array
    {
        return [
            'user',
            'airtime',
            'data',
            'iuc_validation',
            'cabletv',
            'meter_validation',
            'electricity',
        ];
    }

    private function servicePresets(): array
    {
        $electricity = [
            ['name' => 'Ikeja Electricity', 'network_id' => 1],
            ['name' => 'Eko Electricity', 'network_id' => 2],
            ['name' => 'Kano Electricity', 'network_id' => 3],
            ['name' => 'Port Harcourt Electricity', 'network_id' => 4],
            ['name' => 'Jos Electricity', 'network_id' => 5],
            ['name' => 'Ibadan Electricity', 'network_id' => 6],
            ['name' => 'Kaduna Electricity', 'network_id' => 7],
            ['name' => 'Abuja Electricity', 'network_id' => 8],
            ['name' => 'Enugu Electricity', 'network_id' => 9],
            ['name' => 'Benin Electricity', 'network_id' => 10],
        ];

        return [
            'user' => [
                ['name' => 'VTUNaija Wallet', 'network_id' => 1],
            ],
            'airtime' => [
                ['name' => 'mtn', 'network_id' => 1],
                ['name' => 'glo', 'network_id' => 2],
                ['name' => 'airtel', 'network_id' => 4],
                ['name' => '9mobile', 'network_id' => 3],
            ],
            'data' => [
                ['name' => 'mtn', 'network_id' => 1],
                ['name' => 'glo', 'network_id' => 2],
                ['name' => 'airtel', 'network_id' => 4],
                ['name' => '9mobile', 'network_id' => 4],
            ],
            'cabletv' => [
                ['name' => 'gotv', 'network_id' => 1],
                ['name' => 'dstv', 'network_id' => 2],
                ['name' => 'startimes', 'network_id' => 3],
                ['name' => 'showmax', 'network_id' => 4],
            ],
            'iuc_validation' => [
                ['name' => 'IUC Validation', 'network_id' => 2],
            ],
            'electricity' => $electricity,
            'meter_validation' => $electricity,
        ];
    }

    private function providerPresets(): array
    {
        return [
            'wallet' => [
                'category' => 'user',
                'identifier' => 'msorg',
                'api_endpoint' => 'https://vtunaija.com.ng/api/user/',
                'services' => ['VTUNaija Wallet'],
            ],
            'airtime' => [
                'category' => 'airtime',
                'identifier' => 'msorg',
                'api_endpoint' => 'https://vtunaija.com.ng/api/topup/',
                'services' => ['mtn', 'glo', 'airtel', '9mobile'],
            ],
            'data' => [
                'category' => 'data',
                'identifier' => 'msorg2',
                'api_endpoint' => 'https://vtunaija.com.ng/api/data/',
                'services' => ['mtn', 'glo', 'airtel', '9mobile'],
            ],
            'cabletv' => [
                'category' => 'cabletv',
                'identifier' => 'msorg',
                'api_endpoint' => 'https://vtunaija.com.ng/api/cabletv/',
                'services' => ['gotv', 'dstv', 'startimes', 'showmax'],
            ],
            'iuc_validation' => [
                'category' => 'iuc_validation',
                'identifier' => 'msorg',
                'api_endpoint' => 'https://vtunaija.com.ng/api/cabletv/',
                'services' => ['IUC Validation'],
            ],
            'electricity' => [
                'category' => 'electricity',
                'identifier' => 'msorg2',
                'api_endpoint' => 'https://vtunaija.com.ng/api/billpayment/',
                'services' => [
                    'Ikeja Electricity',
                    'Eko Electricity',
                    'Kano Electricity',
                    'Port Harcourt Electricity',
                    'Jos Electricity',
                    'Ibadan Electricity',
                    'Kaduna Electricity',
                    'Abuja Electricity',
                    'Enugu Electricity',
                    'Benin Electricity',
                ],
            ],
            'meter_validation' => [
                'category' => 'meter_validation',
                'identifier' => 'msorg2',
                'api_endpoint' => 'https://vtunaija.com.ng/api/billpayment/',
                'services' => [
                    'Ikeja Electricity',
                    'Eko Electricity',
                    'Kano Electricity',
                    'Port Harcourt Electricity',
                    'Jos Electricity',
                    'Ibadan Electricity',
                    'Kaduna Electricity',
                    'Abuja Electricity',
                    'Enugu Electricity',
                    'Benin Electricity',
                ],
            ],
        ];
    }
}
