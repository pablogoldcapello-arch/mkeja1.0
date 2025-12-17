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
        Schema::create('caretaker_property', function (Blueprint $table) {
            $table->bigIncrements('id');               // Primary key
            $table->unsignedBigInteger('caretaker_id');
            $table->unsignedBigInteger('property_id');

            // who assigned the caretaker (landlord or admin)
            $table->unsignedBigInteger('assigned_by')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('caretaker_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('property_id')
                ->references('id')
                ->on('properties')
                ->onDelete('cascade');

            $table->foreign('assigned_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Prevent duplicate assignments
            $table->unique(['caretaker_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caretaker_property');
    }
};
