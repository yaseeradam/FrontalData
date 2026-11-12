<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'user'],
            ['name' => 'airtime'],
            ['name' => 'data'],
            ['name' => 'iuc_validation'],
            ['name' => 'cabletv'],
            ['name' => 'epins'],
            ['name' => 'meter_validation'],
            ['name' => 'electricity'],
            ['name' => 'recharge_pin'],
            ['name' => 'data_pin'],
            ['name' => 'betting'],
            ['name' => 'bulksms'],
            ['name' => 'a2cash'],
            ['name' => 'p2p'],
        ]);
    }
}
