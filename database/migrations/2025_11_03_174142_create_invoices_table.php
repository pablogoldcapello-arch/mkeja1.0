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
            $table->bigIncrements('id');

            // Recipient
            $table->enum('type', ['tenant', 'service_provider'])->default('tenant');

            // Tenant
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->foreign('tenant_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Property (optional)
            $table->unsignedBigInteger('property_id')->nullable();
            $table->foreign('property_id')
                ->references('id')
                ->on('properties')
                ->onDelete('set null');

            // Service (optional)
            $table->json('services')->nullable(); // stores array of service names (skills)


            // Service Provider
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')
                ->references('id')
                ->on('users')  
                ->onDelete('set null');
    

            // Invoice fields
            $table->string('invoice_number')->unique()->nullable();
            $table->decimal('amount_due', 12, 2);
            $table->string('rent_month')->nullable(); //for tenants
            $table->date('due_date')->nullable();

            $table->enum('status', [
                'draft', 'sent', 'unpaid', 'paid', 'overdue'
            ])->default('draft');

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
