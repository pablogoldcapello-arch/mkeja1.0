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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('profile_photo')->nullable();
            $table->string('profile_photo_url')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->enum('role', ['tenant', 'landlord', 'caretaker', 'techsupport', 'service_provider', 'admin'])->default('tenant'); // controlled roles

            // Role-specific optional fields
            $table->integer('property_count')->default(0); // for landlords
            $table->json('assigned_properties')->nullable(); // for agents/caretakers
            $table->json('skills')->nullable(); // for service providers / tech support

            $table->boolean('is_email_verified')->default(false);
            $table->boolean('2fa_enabled')->default(false);
            $table->enum('status', ['active', 'suspended', 'pending'])->default('active'); // controlled roles

            // Optional personal info
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            
            // Address info
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('postal_code')->nullable();
            
            // System tracking
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
