<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('transactions', 'request_key')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('request_key', 100)->nullable()->unique()->after('session_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'request_key')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('request_key');
            });
        }
    }
};
