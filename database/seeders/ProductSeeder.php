<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            # Airtime
            ['name' => 'mtn', 'service_id' => 1, 'plan_id' => null, 'subcategory' => 'vtu', 'buying_price' => 97, 'reseller_price' => 99, 'agent_price' => 98, 'customer_price' => 99, 'validity' => '365 days'],
            ['name' => 'glo', 'service_id' => 2, 'plan_id' => null, 'subcategory' => 'vtu', 'buying_price' => 97, 'reseller_price' => 99, 'agent_price' => 98, 'customer_price' => 99, 'validity' => '365 days'],
            ['name' => 'airtel', 'service_id' => 3, 'plan_id' => null, 'subcategory' => 'vtu', 'buying_price' => 97, 'reseller_price' => 99, 'agent_price' => 98, 'customer_price' => 99, 'validity' => '365 days'],
            ['name' => '9mobile', 'service_id' => 4, 'plan_id' => null, 'subcategory' => 'vtu', 'buying_price' => 97, 'reseller_price' => 99, 'agent_price' => 98, 'customer_price' => 99, 'validity' => '365 days'],

            # Data
            ['name' => '500MB', 'service_id' => 5, 'plan_id' => 123, 'subcategory' => 'sme', 'buying_price' => 130, 'reseller_price' => 145, 'agent_price' => 150, 'customer_price' => 165, 'validity' => '30 days'],
            ['name' => '1GB', 'service_id' => 5, 'plan_id' => 123, 'subcategory' => 'corporate', 'buying_price' => 250, 'reseller_price' => 280, 'agent_price' => 260, 'customer_price' => 290, 'validity' => '30 days'],
            ['name' => '500MB', 'service_id' => 6, 'plan_id' => 123, 'subcategory' => 'gifting', 'buying_price' => 130, 'reseller_price' => 145, 'agent_price' => 150, 'customer_price' => 160, 'validity' => '30 days'],
            ['name' => '1GB', 'service_id' => 6, 'plan_id' => 123, 'subcategory' => 'sme', 'buying_price' => 250, 'reseller_price' => 280, 'agent_price' => 260, 'customer_price' => 290, 'validity' => '30 days'],
            ['name' => '500MB', 'service_id' => 7, 'plan_id' => 123, 'subcategory' => 'corporate', 'buying_price' => 130, 'reseller_price' => 145, 'agent_price' => 150, 'customer_price' => 160, 'validity' => '30 days'],
            ['name' => '1GB', 'service_id' => 7, 'plan_id' => 123, 'subcategory' => 'gifting', 'buying_price' => 250, 'reseller_price' => 280, 'agent_price' => 260, 'customer_price' => 290, 'validity' => '30 days'],
            ['name' => '500MB', 'service_id' => 8, 'plan_id' => 123, 'subcategory' => 'sme', 'buying_price' => 130, 'reseller_price' => 145, 'agent_price' => 150, 'customer_price' => 160, 'validity' => '30 days'],
            ['name' => '1GB', 'service_id' => 8, 'plan_id' => 123, 'subcategory' => 'corporate', 'buying_price' => 250, 'reseller_price' => 280, 'agent_price' => 260, 'customer_price' => 290, 'validity' => '30 days'],

            # Cable TV
            ['name' => 'DSTV Padi', 'service_id' => 10, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 3600, 'reseller_price' => 3800, 'agent_price' => 3700, 'customer_price' => 3900, 'validity' => '30 days'],
            ['name' => 'DSTV-YANGA', 'service_id' => 10, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 5100, 'reseller_price' => 5400, 'agent_price' => 5300, 'customer_price' => 5600, 'validity' => '30 days'],
            ['name' => 'DSTV Premium-Asia', 'service_id' => 10, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 33000, 'reseller_price' => 33800, 'agent_price' => 33700, 'customer_price' => 33900, 'validity' => '30 days'],
            ['name' => 'DSTV Confam + ExtraView', 'service_id' => 10, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 11500, 'reseller_price' => 11800, 'agent_price' => 11700, 'customer_price' => 11900, 'validity' => '30 days'],
            ['name' => 'DSTV Confam', 'service_id' => 10, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 9300, 'reseller_price' => 9400, 'agent_price' => 9500, 'customer_price' => 9700, 'validity' => '30 days'],
            ['name' => 'DSTV Compact Plus', 'service_id' => 10, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 25000, 'reseller_price' => 25300, 'agent_price' => 25400, 'customer_price' => 25600, 'validity' => '30 days'],
            ['name' => 'DSTV Compact', 'service_id' => 10, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 15700, 'reseller_price' => 15800, 'agent_price' => 15700, 'customer_price' => 15900, 'validity' => '30 days'],
            ['name' => 'GOTV Supa', 'service_id' => 9, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 9600, 'reseller_price' => 9800, 'agent_price' => 9700, 'customer_price' => 9900, 'validity' => '30 days'],
            ['name' => 'GOTV Max', 'service_id' => 9, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 7200, 'reseller_price' => 7400, 'agent_price' => 7300, 'customer_price' => 7500, 'validity' => '30 days'],
            ['name' => 'GOTV Jolli', 'service_id' => 9, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 4850, 'reseller_price' => 5000, 'agent_price' => 4950, 'customer_price' => 5100, 'validity' => '30 days'],
            ['name' => 'GOTV Jinja', 'service_id' => 9, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 3300, 'reseller_price' => 3500, 'agent_price' => 3400, 'customer_price' => 3600, 'validity' => '30 days'],
            ['name' => 'STARTIMES NOVA', 'service_id' => 11, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 1700, 'reseller_price' => 1900, 'agent_price' => 1800, 'customer_price' => 2000, 'validity' => '30 days'],
            ['name' => 'STARTIMES Basic (Antenna)', 'service_id' => 11, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 3000, 'reseller_price' => 3200, 'agent_price' => 3100, 'customer_price' => 3300, 'validity' => '30 days'],
            ['name' => 'STARTIMES Smart (Dish)', 'service_id' => 11, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 3500, 'reseller_price' => 3700, 'agent_price' => 3600, 'customer_price' => 3800, 'validity' => '30 days'],

            #Epins
            ['name' => 'waec', 'service_id' => 13, 'plan_id' => 124, 'subcategory' => null, 'buying_price' => 3500, 'reseller_price' => 3700, 'agent_price' => 3600, 'customer_price' => 3900, 'validity' => '5 Trials'],
            ['name' => 'neco', 'service_id' => 14, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 1200, 'reseller_price' => 1400, 'agent_price' => 1300, 'customer_price' => 3500, 'validity' => '5 Trials'],
            ['name' => 'nabteb', 'service_id' => 15, 'plan_id' => 123, 'subcategory' => null, 'buying_price' => 1000, 'reseller_price' => 1200, 'agent_price' => 1100, 'customer_price' => 3300, 'validity' => '5 Trials'],

            #Electricity
            ['name' => 'Ikeja Electricity', 'service_id' => 16, 'plan_id' => 123, 'subcategory' => 'postpaid', 'buying_price' => 96, 'reseller_price' => 98, 'agent_price' => 97, 'customer_price' => 99, 'validity' => '1 month'],
            ['name' => 'Eko Electricity', 'service_id' => 17, 'plan_id' => 123, 'subcategory' => 'postpaid', 'buying_price' => 96, 'reseller_price' => 98, 'agent_price' => 97, 'customer_price' => 99, 'validity' => '1 month'],
            ['name' => 'Abuja Electricity', 'service_id' => 18, 'plan_id' => 123, 'subcategory' => 'prepaid', 'buying_price' => 96, 'reseller_price' => 98, 'agent_price' => 97, 'customer_price' => 99, 'validity' => '1 month'],
            ['name' => 'Kano Electricity', 'service_id' => 19, 'plan_id' => 123, 'subcategory' => 'prepaid', 'buying_price' => 96, 'reseller_price' => 98, 'agent_price' => 97, 'customer_price' => 99, 'validity' => '1 month'],
            ['name' => 'Port Harcourt Electricity', 'service_id' => 20, 'plan_id' => 123, 'subcategory' => 'prepaid', 'buying_price' => 96, 'reseller_price' => 98, 'agent_price' => 97, 'customer_price' => 99, 'validity' => '1 month'],
            ['name' => 'Jos Electricity', 'service_id' => 21, 'plan_id' => 123, 'subcategory' => 'prepaid', 'buying_price' => 96, 'reseller_price' => 98, 'agent_price' => 97, 'customer_price' => 99, 'validity' => '1 month'],
            ['name' => 'Enugu Electricity', 'service_id' => 22, 'plan_id' => 123, 'subcategory' => 'prepaid', 'buying_price' => 96, 'reseller_price' => 98, 'agent_price' => 97, 'customer_price' => 99, 'validity' => '1 month'],
            ['name' => 'Benin Electricity', 'service_id' => 23, 'plan_id' => 123, 'subcategory' => 'prepaid', 'buying_price' => 96, 'reseller_price' => 98, 'agent_price' => 97, 'customer_price' => 99, 'validity' => '1 month'],
            ['name' => 'Ibadan Electricity', 'service_id' => 24, 'plan_id' => 123, 'subcategory' => 'prepaid', 'buying_price' => 96, 'reseller_price' => 98, 'agent_price' => 97, 'customer_price' => 99, 'validity' => '1 month'],
            ['name' => 'Kaduna Electricity', 'service_id' => 25, 'plan_id' => 123, 'subcategory' => 'prepaid', 'buying_price' => 96, 'reseller_price' => 98, 'agent_price' => 97, 'customer_price' => 99, 'validity' => '1 month'],
            ]);
           }
         }