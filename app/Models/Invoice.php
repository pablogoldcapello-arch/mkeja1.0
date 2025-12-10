<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Invoice extends Model
{
    protected $fillable = [
        // Basic info
        'tenant_id',
        'invoice_number',
        'amount_due', 
        'due_date', 
        'status',
        'rent_month'   

    ];   
    
    public function tenant(){
        return $this->belongsTo(User::class, 'tenant_id');
    }     
}
