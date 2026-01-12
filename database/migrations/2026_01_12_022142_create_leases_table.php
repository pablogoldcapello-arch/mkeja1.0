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
        Schema::create('leases', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relationships
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('tenant_id');

            // Lease lifecycle
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // Status
            $table->enum('status', [
                'active',
                'terminated',
                'expired'
            ])->default('active');

            // Financial snapshot (optional but powerful)
            $table->decimal('monthly_rent', 10, 2)->nullable();
            $table->decimal('deposit', 10, 2)->nullable();

            $table->timestamps();

            // Constraints
            $table->foreign('property_id')
                  ->references('id')->on('properties')
                  ->onDelete('cascade');

            $table->foreign('unit_id')
                  ->references('id')->on('units')
                  ->onDelete('cascade');

            $table->foreign('tenant_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            // One active lease per unit
            $table->unique(['unit_id', 'status'], 'unique_active_lease')
                  ->where('status', 'active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leases');
    }
};
