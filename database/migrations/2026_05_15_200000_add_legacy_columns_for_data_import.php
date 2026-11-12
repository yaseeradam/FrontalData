<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds columns present in the legacy production schema but missing from the
 * bundled migrations. Without these, imports from an older database fail
 * (e.g. "Unknown column 'api_token'") and registering new users also fails
 * because the controller writes to api_token.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'api_token')) {
                $table->string('api_token')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'moniepoint_bank')) {
                $table->string('moniepoint_bank')->nullable()->after('nps_bank');
            }
            if (!Schema::hasColumn('users', 'paga_bank')) {
                $table->string('paga_bank')->nullable()->after('moniepoint_bank');
            }
            if (!Schema::hasColumn('users', 'bankly_bank')) {
                $table->string('bankly_bank')->nullable()->after('paga_bank');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'quantity')) {
                $table->string('quantity')->nullable()->after('product');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['api_token', 'moniepoint_bank', 'paga_bank', 'bankly_bank'] as $col) {
                if (Schema::hasColumn('users', $col)) $table->dropColumn($col);
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'quantity')) $table->dropColumn('quantity');
        });
    }
};
