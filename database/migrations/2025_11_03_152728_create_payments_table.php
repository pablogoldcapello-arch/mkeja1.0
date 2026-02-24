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

            // Tenant
            $table->unsignedBigInteger('tenant_id'); // FK → tenancies.id
            $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');

            // Invoice (optional)
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');

            // Amount
            $table->decimal('amount', 12, 2)->nullable();

            // Transaction details
            $table->string('transaction_code')->nullable();
            $table->enum('payment_method', ['mpesa', 'paypal', 'card', 'cash'])->default('cash');
            $table->enum('status', ['pending', 'successful', 'failed', 'reversed'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->string('checkout_request_id')->nullable();

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
