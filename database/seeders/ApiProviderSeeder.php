<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiProviderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('api_providers')->insert([
            
            #VTU9ja 
            [
                'name' => 'VTU9ja',
                'api_endpoint' => 'https://vtu9ja.ng/api/user/',
                'identifier' => 'adex',
                'category_id' => 1,
            ],
            [
                'name' => 'VTU9ja',
                'api_endpoint' => 'https://vtu9ja.ng/api/airtime/',
                'identifier' => 'adex',
                'category_id' => 2,
            ],
            [
                'name' => 'VTU9ja',
                'api_endpoint' => 'https://vtu9ja.ng/api/data/',
                'identifier' => 'adex',
                'category_id' => 3,
            ],
            [
                'name' => 'VTU9ja',
                'api_endpoint' => 'https://vtu9ja.ng/api/cable/',
                'identifier' => 'adex',
                'category_id' => 5,
            ],
            [
                'name' => 'VTU9ja',
                'api_endpoint' => 'https://vtu9ja.ng/api/cable/cable-validation/',
                'identifier' => 'adex',
                'category_id' => 4,
            ],
            [
                'name' => 'VTU9ja',
                'api_endpoint' => 'https://vtu9ja.ng/api/exam/',
                'identifier' => 'adex',
                'category_id' => 6,
            ],
            [
                'name' => 'VTU9ja',
                'api_endpoint' => 'https://vtu9ja.ng/api/bill/',
                'identifier' => 'adex',
                'category_id' => 8,
            ],
            [
                'name' => 'VTU9ja',
                'api_endpoint' => 'https://vtu9ja.ng/bill/bill-validation/',
                'identifier' => 'adex',
                'category_id' => 7,
            ],
            
            #VTUnaija.com.ng
            [
                'name' => 'VTUnaija.com.ng',
                'api_endpoint' => 'https://vtunaija.com.ng/api/user/',
                'identifier' => 'mate',
                'category_id' => 1,
            ],
            [
                'name' => 'VTUnaija.com.ng',
                'api_endpoint' => 'https://vtunaija.com.ng/api/topup/',
                'identifier' => 'mate',
                'category_id' => 2,
            ],
            [
                'name' => 'VTUnaija.com.ng',
                'api_endpoint' => 'https://vtunaija.com.ng/api/data/',
                'identifier' => 'mate',
                'category_id' => 3,
            ],
            [
                'name' => 'VTUnaija.com.ng',
                'api_endpoint' => 'https://vtunaija.com.ng/api/cabletv/verify',
                'identifier' => 'mate',
                'category_id' => 4,
            ],
            [
                'name' => 'VTUnaija.com.ng',
                'api_endpoint' => 'https://vtunaija.com.ng/api/cabletv/',
                'identifier' => 'mate',
                'category_id' => 5,
            ],
            [
                'name' => 'VTUnaija.com.ng',
                'api_endpoint' => 'https://vtunaija.com.ng/api/exam/',
                'identifier' => 'mate',
                'category_id' => 6,
            ],
            [
                'name' => 'VTUnaija.com.ng',
                'api_endpoint' => 'https://vtunaija.com.ng/api/electricity/verify',
                'identifier' => 'mate',
                'category_id' => 7,
            ],
            [
                'name' => 'VTUnaija.com.ng',
                'api_endpoint' => 'https://vtunaija.com.ng/api/electricity/',
                'identifier' => 'mate',
                'category_id' => 8,
            ],
            
            #Mansurdata 
            [
                'name' => 'Mansurdata',
                'api_endpoint' => 'https://mansurdata.net/api/user/',
                'identifier' => 'adex',
                'category_id' => 1,
            ],
            [
                'name' => 'Mansurdata',
                'api_endpoint' => 'https://mansurdata.net/api/airtime/',
                'identifier' => 'adex',
                'category_id' => 2,
            ],
            [
                'name' => 'Mansurdata',
                'api_endpoint' => 'https://mansurdata.net/api/data/',
                'identifier' => 'adex',
                'category_id' => 3,
            ],
            [
                'name' => 'Mansurdata',
                'api_endpoint' => 'https://mansurdata.net/api/cable/',
                'identifier' => 'adex',
                'category_id' => 5,
            ],
            [
                'name' => 'Mansurdata',
                'api_endpoint' => 'https://mansurdata.net/api/cable/cable-validation/',
                'identifier' => 'adex',
                'category_id' => 4,
            ],
            [
                'name' => 'Mansurdata',
                'api_endpoint' => 'https://mansurdata.net/api/exam/',
                'identifier' => 'adex',
                'category_id' => 6,
            ],
            [
                'name' => 'Mansurdata',
                'api_endpoint' => 'https://mansurdata.net/api/bill/',
                'identifier' => 'adex',
                'category_id' => 8,
            ],
            [
                'name' => 'Mansurdata',
                'api_endpoint' => 'https://mansurdata.net/bill/bill-validation/',
                'identifier' => 'adex',
                'category_id' => 7,
            ],
            
            #Rennytelecom
            [
                'name' => 'Rennytelecom',
                'api_endpoint' => 'https://rennytelecoms.com.ng/api/user/',
                'identifier' => 'adex',
                'category_id' => 1,
            ],
            [
                'name' => 'Rennytelecom',
                'api_endpoint' => 'https://rennytelecoms.com.ng/api/airtime/',
                'identifier' => 'adex',
                'category_id' => 2,
            ],
            [
                'name' => 'Rennytelecom',
                'api_endpoint' => 'https://rennytelecoms.com.ng/api/data/',
                'identifier' => 'adex',
                'category_id' => 3,
            ],
            [
                'name' => 'Rennytelecom',
                'api_endpoint' => 'https://rennytelecoms.com.ng/api/cable/',
                'identifier' => 'adex',
                'category_id' => 5,
            ],
            [
                'name' => 'Rennytelecom',
                'api_endpoint' => 'https://rennytelecoms.com.ng/api/cable/cable-validation/',
                'identifier' => 'adex',
                'category_id' => 4,
            ],
            [
                'name' => 'Rennytelecom',
                'api_endpoint' => 'https://rennytelecoms.com.ng/api/exam/',
                'identifier' => 'adex',
                'category_id' => 6,
            ],
            [
                'name' => 'Rennytelecom',
                'api_endpoint' => 'https://rennytelecoms.com.ng/api/bill/',
                'identifier' => 'adex',
                'category_id' => 8,
            ],
            [
                'name' => 'Rennytelecom',
                'api_endpoint' => 'https://rennytelecoms.com.ng/bill/bill-validation/',
                'identifier' => 'adex',
                'category_id' => 7,
            ],
        ]);
    }
}