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
        Schema::create('units', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Foreign Key → properties.id
            $table->unsignedBigInteger('property_id');
            $table->foreign('property_id')
                ->references('id')
                ->on('properties')
                ->onDelete('cascade');

            // Basic unit details
            $table->string('unit_number')->nullable();
            $table->string('type')->nullable();

            // Rent-related values
            $table->decimal('deposit', 10, 2)->nullable();
            $table->decimal('monthly_rent', 10, 2)->nullable();

            // Additional Fees
            $table->decimal('garbage_fee', 10, 2)->nullable();
            $table->decimal('security_fee', 10, 2)->nullable();

            // Water
            $table->string('water_meter')->nullable();
            $table->decimal('water_deposit', 10, 2)->nullable();

            // Electricity
            $table->string('electricity_meter')->nullable();
            $table->decimal('electricity_deposit', 10, 2)->nullable();

            // Payment Details
            $table->string('paybill_number')->nullable();
            $table->string('account_number')->nullable();

            // Unit Status
            $table->enum('status', ['vacant', 'rented', 'maintenance'])
                ->default('vacant');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
