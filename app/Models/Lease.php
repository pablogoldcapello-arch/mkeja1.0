<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Unit;
use App\Models\Property;

class Lease extends Model
{    protected $fillable = [
        'property_id',
        'unit_id',
        'tenant_id',
        'start_date',
        'end_date',
        'status',
        'monthly_rent',
        'deposit',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    
}
