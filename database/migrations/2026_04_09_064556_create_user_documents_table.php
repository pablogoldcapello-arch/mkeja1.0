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
        Schema::create('user_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // links to users table
            $table->string('type'); // e.g., 'landlord_agreement', 'tenant_agreement', 'service_provider_agreement'
            $table->string('file_name'); // stored file name in storage
            $table->string('original_name'); // original uploaded file name
            $table->string('file_url', 1000); // URL to access the file
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // optional workflow
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};
