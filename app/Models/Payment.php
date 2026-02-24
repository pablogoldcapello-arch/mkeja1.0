<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LedgerEntry;
use App\Models\Tenancy;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    // Mass assignable fields
    protected $fillable = [
        'tenant_id',
        'amount',
        'transaction_code',
        'payment_method',
        'status',
        'payment_date',
        'invoice_id',
        'checkout_request_id'
    ];

    // Cast fields to proper types
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Relationships
     */

    // Payment belongs to a tenancy/tenant
    public function tenant()
    {
        return $this->belongsTo(Tenancy::class, 'tenant_id');
    }

    // Optional: link to ledger entries
    public function ledgerEntries()
    {
        return $this->morphMany(LedgerEntry::class, 'reference');
    }

    /**
     * Helper Methods
     */

    public function isSuccessful()
    {
        return $this->status === 'successful';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isReversed()
    {
        return $this->status === 'reversed';
    }


    
}