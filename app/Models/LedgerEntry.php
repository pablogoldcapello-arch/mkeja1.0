<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\User;

class LedgerEntry extends Model
{
    protected $fillable = [
        'landlord_id',
        'property_id',
        'unit_id',
        'tenant_id',
        'entry_type',
        'amount',
        'currency',
        'rent_period',
        'reference_type',
        'reference_id',
        'created_by',
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }    
}
