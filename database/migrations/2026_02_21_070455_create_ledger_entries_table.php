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
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('landlord_id')->constrained('users');
            $table->foreignId('property_id')->nullable()->constrained();
            $table->foreignId('unit_id')->nullable()->constrained();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->foreign('tenant_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->enum('entry_type', [
                'rent_charge',
                'payment',
                'penalty',
                'adjustment',
                'write_off',
                'refund',
                'platform_fee'
            ]);

            $table->decimal('amount', 12, 2); // +ve or -ve
            $table->string('currency')->default('KES');

            $table->string('rent_period'); // YYYY-MM (critical for KPIs)

            $table->string('reference_type')->nullable(); // invoice, payment, manual
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users');

            $table->timestamps();

            // KPI performance indexes
            $table->index(['landlord_id', 'rent_period']);
            $table->index(['unit_id', 'rent_period']);
            $table->index('entry_type');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
