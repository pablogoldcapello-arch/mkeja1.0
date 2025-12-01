<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Listing;

class Image extends Model
{
    protected $fillable = [
        'listing_id', // optional if you always set via relationship
        'name',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }    
}
