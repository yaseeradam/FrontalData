<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('services')->insert([
            #Airtime
            ['name' => 'mtn', 'category_id' => 2, 'network_id' => 1],
            ['name' => 'glo', 'category_id' => 2, 'network_id' => 2],
            ['name' => 'airtel', 'category_id' => 2, 'network_id' => 3],
            ['name' => '9mobile', 'category_id' => 2, 'network_id' => 4],
            
            #Data
            ['name' => 'mtn', 'category_id' => 3, 'network_id' => 1],
            ['name' => 'glo', 'category_id' => 3, 'network_id' => 2],
            ['name' => 'airtel', 'category_id' => 3, 'network_id' => 3],
            ['name' => '9mobile', 'category_id' => 3, 'network_id' => 4],
            
            #CableTV
            ['name' => 'gotv', 'category_id' => 5, 'network_id' => 1],
            ['name' => 'dstv', 'category_id' => 5, 'network_id' => 2],
            ['name' => 'startimes', 'category_id' => 5, 'network_id' => 3],
            ['name' => 'showmax', 'category_id' => 5, 'network_id' => 4],
            
            #Epins
            ['name' => 'waec', 'category_id' => 6, 'network_id' => 1],
            ['name' => 'neco', 'category_id' => 6, 'network_id' => 2],
            ['name' => 'nabteb', 'category_id' => 6, 'network_id' => 3],
            
            #Electricity 
            ['name' => 'Ikeja Electricity', 'category_id' => 8, 'network_id' => 1],
            ['name' => 'Eko Electricity', 'category_id' => 8, 'network_id' => 2],
            ['name' => 'Kano Electricity', 'category_id' => 8, 'network_id' => 3],
            ['name' => 'Port Harcourt Electricity', 'category_id' => 8, 'network_id' => 4],
            ['name' => 'Jos Electricity', 'category_id' => 8, 'network_id' => 5],
            ['name' => 'Ibadan Electricity', 'category_id' => 8, 'network_id' => 6],
            ['name' => 'Kaduna Electricity', 'category_id' => 8, 'network_id' => 7],
            ['name' => 'Abuja Electricity', 'category_id' => 8, 'network_id' => 8],
            ['name' => 'Benin Electricity', 'category_id' => 8, 'network_id' => 8],
            ['name' => 'Enugu Electricity', 'category_id' => 8, 'network_id' => 10],
        ]);
    }
}