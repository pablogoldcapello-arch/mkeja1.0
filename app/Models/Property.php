<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Property extends Model
{
    protected $fillable = [
        'landlord_id',
        'agent_id',
        'title',
        'description',
        'type',
        'location',
        'coordinates',
        'rent_amount',
        'status'
    ];  
    
    /**
     * Get the landlord (user) that owns the property
     */
    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    /**
     * Get the agent (user) who manages the property
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // Add this relationship
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
    
}
