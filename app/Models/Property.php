<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Unit;
use App\Models\SupportTicket;

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

    public function caretakers()
    {
        return $this->belongsToMany(
            User::class,
            'caretaker_property',
            'property_id',
            'caretaker_id'
        )->withTimestamps();
    }

    public function tickets()
    {
        return SupportTicket::whereIn('user_id', function($q) {
            $q->select('tenant_id')->from('leases')->where('status','active');
        });
    }


    
}
