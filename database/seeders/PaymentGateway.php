<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PaymentGateway extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_gateways')->insert([
            [
                'name' => 'Payvessel',
                'api_token' => '',
                'secret_token' => '',
                'charge_method' => 'fixed',
                'business_id' => '22778495429',
                'charges' => "40",
                'identifier' => 'pv',
                'optional' => '',
                'status' => 'enabled',
            ],
            
            [
                'name' => 'Monnify',
                'api_token' => '',
                'secret_token' => '',
                'charge_method' => 'fixed',
                'business_id' => '22778495429',
                'charges' => "40",
                'identifier' => 'mf',
                'optional' => '',
                'status' => 'disabled',
            ],
            
            [
                'name' => 'Billstack',
                'api_token' => '',
                'secret_token' => '',
                'charge_method' => 'fixed',
                'business_id' => '22778495429',
                'charges' => "40",
                'identifier' => 'bs',
                'optional' => '',
                'status' => 'disabled',
            ],
            
            [
                'name' => 'NCwallet',
                'api_token' => '',
                'secret_token' => '',
                'charge_method' => 'fixed',
                'business_id' => '22778495429',
                'charges' => "40",
                'identifier' => 'nc',
                'optional' => '',
                'status' => 'disabled',
            ],
        
        ]);
    }
}
