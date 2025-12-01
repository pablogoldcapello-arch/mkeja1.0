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
        Schema::create('listings', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            // Basic info
            $table->string('title', 100);
            $table->longText('description')->nullable();
            $table->enum('type', ['apartment', 'house', 'bedsitter', 'studio', 'office', 'land'])->default('house');
            $table->enum('status', ['for_sale', 'for_rent', 'sold', 'occupied'])->default('for_rent');

            // Location
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('coordinates')->nullable();

            // Property details
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('living_rooms')->nullable();
            $table->integer('kitchens')->nullable();
            $table->boolean('balcony')->default(false);
            $table->integer('floor_level')->nullable();
            $table->float('total_area')->nullable();
            $table->boolean('furnished')->default(false);

            // Financials
            $table->decimal('price', 15, 2)->nullable();
            $table->string('currency', 10)->default('KES');
            $table->decimal('deposit', 15, 2)->nullable();
            $table->string('payment_terms')->nullable();

            // Amenities & Facilities
            $table->boolean('parking')->default(false);
            $table->integer('parking_spaces')->nullable();
            $table->boolean('security')->default(false);
            $table->boolean('water_supply')->default(true);
            $table->boolean('electricity')->default(true);
            $table->boolean('internet')->default(false);
            $table->boolean('swimming_pool')->default(false);
            $table->boolean('gym')->default(false);
            $table->boolean('garden')->default(false);
            $table->boolean('elevator')->default(false);

            // Media
            $table->string('main_image')->nullable();
            $table->string('video_tour')->nullable();
            $table->string('floor_plan')->nullable();

            // Owner / Agent info
            $table->unsignedBigInteger('user_id'); // owner or agent
            $table->string('contact_phone')->nullable(); 
            $table->string('contact_email')->nullable();

            // Additional attributes
            $table->year('year_built')->nullable();
            $table->boolean('renovated')->default(false);
            $table->string('special_features')->nullable();

            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
