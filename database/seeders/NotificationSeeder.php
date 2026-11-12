<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        DB::table('notifications')->insert([
            [
                'admin_id' => 1,
                'title' => 'Sample Notification',
                'message' => 'This is a sample notification message.',
                'action_url' => 'https://example.com/action',
                'status' => 'active',
                'date' => now(),
            ],
            
            [
                'admin_id' => 1,
                'title' => 'Get Amazing Offer',
                'message' => 'Enjoy 20% on all data plan offers view now.',
                'action_url' => 'https://example.com/action',
                'status' => 'active',
                'date' => now(),
            ],
        
        ]);
    }
}