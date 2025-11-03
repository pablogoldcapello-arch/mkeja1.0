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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('tenant_id');       // FK → tenancies.id
            $table->decimal('amount')->nullable();
            $table->string('transaction_code')->nullable();
            $table->enum('payment_method', ['mpesa', 'paypal', 'card','cash'])->default('cash'); // controlled roles            
            $table->enum('status', ['pending', 'successful', 'failed','reversed'])->default('pending'); // controlled roles            
            $table->date('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
