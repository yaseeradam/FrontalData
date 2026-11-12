<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->string('subcategory')->nullable();
            $table->decimal('buying_price', 10, 2);
            $table->decimal('reseller_price', 10, 2);
            $table->decimal('agent_price', 10, 2);
            $table->decimal('customer_price', 10, 2);
            $table->string('validity')->nullable();
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
