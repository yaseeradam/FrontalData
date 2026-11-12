<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $this->call([
            OptionSeeder::class,
            CategoriesSeeder::class,
            ServiceSeeder::class,
            PaymentGateway::class,
            ApiProviderSeeder::class,
            ProductSeeder::class,
            ApiConfigSeeder::class,
            ]);
    }
}
