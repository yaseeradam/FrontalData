<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiConfigSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('api_configs')->insert([
            ['service_id' => 1, 'provider_id' => 2, 'api_token' => 'api_token', 'status' => 'enabled', 'optional' => null],
            ['service_id' => 2, 'provider_id' => 2, 'api_token' => 'api_token', 'status' => 'enabled', 'optional' => null],
        ]);
    }
}