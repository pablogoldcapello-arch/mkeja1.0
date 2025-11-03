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
        Schema::create('tenancies', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('tenant_id');       // FK → users.id
            $table->unsignedBigInteger('unit_id');       // FK → units.id
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('deposit_amount')->nullable();
            $table->enum('status', ['vacant', 'rented', 'maintenance'])->default('vacant'); // controlled roles            
            $table->foreign( 'tenant_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign( 'unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenancies');
    }
};
