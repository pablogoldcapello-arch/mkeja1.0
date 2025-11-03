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
        Schema::create('disputes', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('ticket_id');       // FK → support_tickets.id
            $table->unsignedBigInteger('reporter_id');       // FK → users.id
            $table->unsignedBigInteger('respondent_id');       // FK → users.id
            $table->enum('issue_type', ['payment', 'misconduct', 'fraud','property','service'])->default('payment');             
            $table->enum('status', ['open', 'under_review', 'resolved','escalated','closed'])->default('open');             
            $table->longText('resolution_summary')->nullable();
            $table->foreign( 'ticket_id')->references('id')->on('support_tickets')->onDelete('cascade');
            $table->foreign( 'reporter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign( 'respondent_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
