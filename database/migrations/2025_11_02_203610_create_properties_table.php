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
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('landlord_id');       // FK → users.id
            $table->unsignedBigInteger('agent_id');       // FK → users.id
            $table->foreign('landlord_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign( 'agent_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title', 20)->nullable(); //  rarely exceed 20 chars
            $table->longText('description')->nullable(); //  rarely exceed 20 chars
            $table->enum('type', [
                'apartment', 'house', 'bedsitter', 'studio', 'office', 'land'
            ])->default('house');
            $table->string('location', 20)->nullable(); //  rarely exceed 20 chars
            $table->string('coordinates', 20)->nullable(); //  rarely exceed 20 chars
            $table->integer('units_no')->default(0); //  rarely exceed 20 chars
            $table->enum('status', ['available', 'occupied', 'under_maintenance'])->default('available'); // controlled roles            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
