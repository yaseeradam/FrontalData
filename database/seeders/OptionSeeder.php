<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    public function run()
    {
        $options = [
            ['opt_key' => 'site_name', 'opt_value' => 'Jannah VTU'],
            ['opt_key' => 'site_url', 'opt_value' => 'https://example.com'],
            ['opt_key' => 'primary_color', 'opt_value' => '#0d4f3c'],
            ['opt_key' => 'secondary_color', 'opt_value' => '#c8a26b'],
            ['opt_key' => 'registration_bonus', 'opt_value' => '500'],
            ['opt_key' => 'registration_bonus_referral', 'opt_value' => '100'],
            ['opt_key' => 'support_phone', 'opt_value' => '+1234567890'],
            ['opt_key' => 'support_email', 'opt_value' => 'support@example.com'],
            ['opt_key' => 'account_number', 'opt_value' => '123456789'],
            ['opt_key' => 'bank_name', 'opt_value' => ''],
            ['opt_key' => 'max_user_transaction', 'opt_value' => '10000'],
            ['opt_key' => 'max_reseller_transaction', 'opt_value' => '50000'],
            ['opt_key' => 'max_agent_transaction', 'opt_value' => '100000'],
            ['opt_key' => 'airtime_feature', 'opt_value' => 'enabled'],
            ['opt_key' => 'data_feature', 'opt_value' => 'enabled'],
            ['opt_key' => 'electricity_feature', 'opt_value' => 'enabled'],
            ['opt_key' => 'epin_feature', 'opt_value' => 'enabled'],
            ['opt_key' => 'tv_feature', 'opt_value' => 'enabled'],
            ['opt_key' => 'kyc_status', 'opt_value' => 'enabled'],
            ['opt_key' => 'wallet_transfer_charges', 'opt_value' => '10'],
            ['opt_key' => 'max_wallet_transfer', 'opt_value' => '5000'],
            ['opt_key' => 'min_wallet_transfer', 'opt_value' => '100'],
            ['opt_key' => 'referral_earning_percentage', 'opt_value' => '5'],
            ['opt_key' => 'agent_upgrade_earning', 'opt_value' => '2000'],
            ['opt_key' => 'reseller_upgrade_earning', 'opt_value' => '5000'],
            ['opt_key' => 'agent_upgrade_charges', 'opt_value' => '10000'],
            ['opt_key' => 'reseller_upgrade_charges', 'opt_value' => '20000'],

            // Added to match the full production option set so fresh installs
            // expose every setting on the admin Settings page.
            ['opt_key' => 'account_name', 'opt_value' => 'Jannah VTU'],
            ['opt_key' => 'preloader_status', 'opt_value' => 'disabled'],
            ['opt_key' => 'preferred_theme', 'opt_value' => 'default'],
            ['opt_key' => 'twilio_account_sid', 'opt_value' => 'your_twilio_account_sid'],
            ['opt_key' => 'twilio_auth_token', 'opt_value' => 'your_twilio_auth_token'],
            ['opt_key' => 'twilio_phone_number', 'opt_value' => 'your_twilio_phone_number'],
            ['opt_key' => 'google_client_id', 'opt_value' => 'your_google_client_id'],
            ['opt_key' => 'google_client_secret', 'opt_value' => 'your_google_client_secret'],
            ['opt_key' => 'recharge_feature', 'opt_value' => 'enabled'],
            ['opt_key' => 'betting_feature', 'opt_value' => 'disabled'],
            ['opt_key' => 'support_whatsapp', 'opt_value' => 'false'],
            ['opt_key' => 'support_instagram', 'opt_value' => 'false'],
            ['opt_key' => 'support_facebook', 'opt_value' => 'false'],
            ['opt_key' => 'support_telegram', 'opt_value' => 'false'],
            ['opt_key' => 'support_youtube', 'opt_value' => 'false'],
            ['opt_key' => 'support_group', 'opt_value' => 'false'],
            ['opt_key' => 'homepage_status', 'opt_value' => 'disabled'],
            ['opt_key' => 'show_popup', 'opt_value' => 'enabled'],
        ];

        // insertOrIgnore keyed on the unique opt_key — safe to re-run, and won't
        // clobber rows an operator has already customised.
        DB::table('options')->insertOrIgnore($options);
    }
}