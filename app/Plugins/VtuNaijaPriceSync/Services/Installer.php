<?php

namespace App\Plugins\VtuNaijaPriceSync\Services;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Installer
{
    public static function install(): void
    {
        self::createMarkupRulesTable();
        self::createMappingsTable();
        self::createRunsTable();
        self::createRunItemsTable();
    }

    private static function createMarkupRulesTable(): void
    {
        if (Schema::hasTable('vtunaija_price_sync_markup_rules')) {
            self::ensureMarkupRuleColumns();
            return;
        }

        Schema::create('vtunaija_price_sync_markup_rules', function (Blueprint $table) {
            $table->id();
            $table->string('product_type')->nullable();
            $table->string('scope_type')->default('global');
            $table->string('scope_key')->nullable();
            $table->decimal('price_min', 12, 4)->nullable();
            $table->decimal('price_max', 12, 4)->nullable();
            $table->string('markup_type')->default('flat');
            $table->decimal('markup_value', 12, 4)->default(0);
            $table->decimal('customer_markup_value', 12, 4)->nullable();
            $table->decimal('reseller_markup_value', 12, 4)->nullable();
            $table->decimal('agent_markup_value', 12, 4)->nullable();
            $table->timestamps();
            $table->index(['product_type', 'scope_type', 'scope_key'], 'vtunaija_markup_scope_idx');
        });
    }

    private static function ensureMarkupRuleColumns(): void
    {
        $columns = [
            'price_min' => 'scope_key',
            'price_max' => 'price_min',
            'customer_markup_value' => 'markup_value',
            'reseller_markup_value' => 'customer_markup_value',
            'agent_markup_value' => 'reseller_markup_value',
        ];

        foreach ($columns as $column => $after) {
            if (!Schema::hasColumn('vtunaija_price_sync_markup_rules', $column)) {
                Schema::table('vtunaija_price_sync_markup_rules', function (Blueprint $table) use ($column, $after) {
                    $table->decimal($column, 12, 4)->nullable()->after($after);
                });
            }
        }
    }

    private static function createMappingsTable(): void
    {
        if (Schema::hasTable('vtunaija_price_sync_mappings')) {
            return;
        }

        Schema::create('vtunaija_price_sync_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('product_type');
            $table->string('external_id');
            $table->unsignedBigInteger('product_id');
            $table->json('source_snapshot')->nullable();
            $table->timestamps();
            $table->unique(['product_type', 'external_id'], 'vtunaija_mapping_external_unique');
            $table->index('product_id');
        });
    }

    private static function createRunsTable(): void
    {
        if (Schema::hasTable('vtunaija_price_sync_runs')) {
            return;
        }

        Schema::create('vtunaija_price_sync_runs', function (Blueprint $table) {
            $table->id();
            $table->string('product_type');
            $table->string('status')->default('previewed');
            $table->unsignedInteger('fetched_count')->default(0);
            $table->unsignedInteger('created_count')->default(0);
            $table->unsignedInteger('updated_count')->default(0);
            $table->unsignedInteger('skipped_count')->default(0);
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    private static function createRunItemsTable(): void
    {
        if (Schema::hasTable('vtunaija_price_sync_run_items')) {
            return;
        }

        Schema::create('vtunaija_price_sync_run_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('run_id');
            $table->string('product_type');
            $table->string('external_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('action');
            $table->string('status')->default('pending');
            $table->json('before_snapshot')->nullable();
            $table->json('after_snapshot')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
            $table->index(['run_id', 'product_type']);
        });
    }
}
