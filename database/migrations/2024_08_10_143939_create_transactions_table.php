<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('biller')->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->string('product')->nullable();
            $table->string('session_id')->nullable()->index();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('number', 15)->nullable();
            $table->string('status', 50)->nullable();
            $table->decimal('old_balance', 10, 2)->nullable();
            $table->decimal('new_balance', 10, 2)->nullable();
            $table->decimal('profits', 10, 2)->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
     
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
