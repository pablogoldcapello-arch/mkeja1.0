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
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('tenant_id');       // FK → tenancies.id
            $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid');             
            $table->string('invoice_number')->nullable();
            $table->decimal('amount_due')->nullable();
            $table->date('due_date')->nullable();
            $table->foreign( 'tenant_id')->references('id')->on('users')->onDelete('cascade');
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
