<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\User;

class Listing extends Model
{
    protected $table = 'listings';

    protected $fillable = [
        // Basic info
        'title',
        'description',
        'type',       // apartment, house, etc.
        'status',     // for_sale, for_rent, etc.

        // Location
        'address',
        'city',
        'neighborhood',
        'coordinates',

        // Property details
        'bedrooms',
        'bathrooms',
        'living_rooms',
        'kitchens',
        'balcony',
        'floor_level',
        'total_area',
        'furnished',

        // Financials
        'price',
        'currency',
        'deposit',
        'payment_terms',

        // Amenities & Facilities
        'parking',
        'parking_spaces',
        'security',
        'water_supply',
        'electricity',
        'internet',
        'swimming_pool',
        'gym',
        'garden',
        'elevator',

        // Media
        'main_image',
        'video_tour',
        'floor_plan',

        // Owner / Agent info
        'user_id',
        'contact_phone',
        'contact_email',

        // Additional attributes
        'year_built',
        'renovated',
        'special_features',
    ];

    protected $casts = [
        'balcony' => 'boolean',
        'furnished' => 'boolean',
        'parking' => 'boolean',
        'security' => 'boolean',
        'water_supply' => 'boolean',
        'electricity' => 'boolean',
        'internet' => 'boolean',
        'swimming_pool' => 'boolean',
        'gym' => 'boolean',
        'garden' => 'boolean',
        'elevator' => 'boolean',
        'renovated' => 'boolean',
        'price' => 'float',
        'deposit' => 'float',
        'total_area' => 'float',
    ];   

    public function images(){
        return $this->hasMany(Image::class, 'listing_id');
    }    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
