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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            
            // Which tenant is this invoice for
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->foreign('tenant_id')->references('id')->on('users')->onDelete('cascade');

            // Optional reference to property or service
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();

            // Invoice fields
            $table->string('invoice_number')->nullable();
            $table->decimal('amount_due', 12, 2)->nullable();
            $table->string('rent_month')->nullable();
            $table->date('due_date')->nullable();

            // Status: matches frontend logic
            $table->enum('status', ['draft','sent','unpaid','paid','overdue'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
