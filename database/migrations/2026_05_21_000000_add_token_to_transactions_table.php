<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('transactions', 'token')) {
            Schema::table('transactions', function (Blueprint $table) {
                // Electricity prepaid token / any post-purchase delivery code.
                $table->string('token')->nullable()->after('number');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('transactions', 'token')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('token');
            });
        }
    }
};
