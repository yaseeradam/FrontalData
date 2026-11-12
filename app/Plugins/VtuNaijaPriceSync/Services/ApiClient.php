<?php

namespace App\Plugins\VtuNaijaPriceSync\Services;

use App\Models\ApiConfig;
use Illuminate\Support\Facades\Http;

class ApiClient
{
    private const BASE_URL = 'https://vtunaija.com.ng';

    public function fetch(string $endpoint): array
    {
        $config = $this->apiConfig();

        if (!$config) {
            return [
                'ok' => false,
                'message' => 'No enabled VTUNAIJA API configuration was found.',
                'data' => [],
            ];
        }

        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Token ' . $config->api_token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post(self::BASE_URL . $endpoint, new \stdClass());
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'message' => 'VTUNAIJA could not be reached: ' . $e->getMessage(),
                'data' => [],
            ];
        }

        if (!$response->ok()) {
            return [
                'ok' => false,
                'message' => 'VTUNAIJA returned HTTP ' . $response->status() . '.',
                'data' => $response->json() ?: [],
            ];
        }

        $data = $response->json() ?: [];
        $successful = strtolower((string) ($data['status'] ?? $data['Status'] ?? '')) === 'success'
            || strtolower((string) ($data['Status'] ?? '')) === 'successful';

        return [
            'ok' => $successful,
            'message' => $successful ? null : ($data['message'] ?? 'VTUNAIJA returned a failed response.'),
            'data' => $data,
        ];
    }

    public function hasConfig(): bool
    {
        return (bool) $this->apiConfig();
    }

    private function apiConfig(): ?ApiConfig
    {
        return ApiConfig::query()
            ->where('status', 'enabled')
            ->whereHas('apiProvider', function ($query) {
                $query->where('name', 'like', '%vtunaija%')
                    ->orWhere('identifier', 'like', '%vtunaija%')
                    ->orWhere('api_endpoint', 'like', '%vtunaija%');
            })
            ->with('apiProvider')
            ->latest()
            ->first();
    }
}
