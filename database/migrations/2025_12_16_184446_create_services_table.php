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
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');               // Primary key
            $table->string('name');                    // Service name (e.g., Cleaning, Repairs)
            $table->unsignedBigInteger('provider_id'); // FK → users.id (Service Provider)
            $table->decimal('default_rate', 10, 2)->nullable(); // Default charge for the service
            $table->text('description')->nullable();   // Optional description
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Foreign keys
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
