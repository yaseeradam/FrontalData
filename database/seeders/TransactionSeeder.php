<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Transaction;

/**
 * Seed the transactions table with sample data.
 */
class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Retrieve the first existing user.
         *
         * @var \App\Models\User|null $user
         */
        $user = User::first();

        if ($user) {
            /**
             * Create sample transactions for the retrieved user.
             */
            Transaction::create([
                'user_id' => $user->id,
                'biller' => 'GLO',
                'description' => 'Purchase of 10GB GLO for 08074195479',
                'type' => 'debit',
                'product' => 'data',
                'session_id' => '202408100357161234',
                'amount' => 1500.00,
                'number' => '08074195479',
                'status' => 'successful',
                'old_balance' => 5000.00,
                'new_balance' => 3500.00,
                'profits' => 100.00,
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'biller' => 'MTN',
                'description' => 'Purchase of 10GB MTN for 08134489526',
                'type' => 'debit',
                'product' => 'data',
                'session_id' => '202408100357161233',
                'amount' => 2000.00,
                'number' => '08134489526',
                'status' => 'successful',
                'old_balance' => 3500.00,
                'new_balance' => 1500.00,
                'profits' => 200.00,
            ]);
        } else {
            /**
             * Inform the user if no existing user was found.
             */
            $this->command->info('No user found to associate transactions with.');
        }
    }
}
