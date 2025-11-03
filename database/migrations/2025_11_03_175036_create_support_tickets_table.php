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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('user_id');       // FK → users.id
            $table->unsignedBigInteger('assigned_to');       // FK → users.id
            $table->enum('category', ['payment', 'property', 'service','agent','technical','fraud'])->default('payment');             
            $table->enum('priority', ['low', 'medium', 'high','critical'])->default('medium');             
            $table->enum('status', ['open', 'in progress', 'resolved','closed'])->default('open');             
            $table->longText('description')->nullable();
            $table->longText('resolution')->nullable();
            $table->foreign( 'user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign( 'assigned_to')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
