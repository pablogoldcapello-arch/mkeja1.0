<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Property;
use App\Models\Unit;

class Tenancy extends Model
{
    // Allow mass assignment for these columns
    protected $fillable = [
        'tenant_id',
        'property_id',
        'unit_id',
        'start_date',
        'end_date',
        'status',
    ];

    // Cast dates properly
    protected $dates = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    /**
     * The tenant (user) associated with this tenancy
     */
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    /**
     * The property associated with this tenancy
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * The unit associated with this tenancy (optional)
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}

