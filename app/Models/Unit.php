<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Property;

class Unit extends Model
{
    protected $fillable = [
        'property_id',
        'unit_number',
        'type',
        'deposit',
        'monthly_rent',
        'garbage_fee',
        'security_fee',
        'water_meter',
        'water_deposit',
        'electricity_meter',
        'electricity_deposit',
        'paybill_number',
        'account_number',
        'status'
    ];  
    
    public function property()
    {
        return $this->belongsTo(Property::class,'pms_property_id');
    }    
}
