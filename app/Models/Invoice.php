<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Property;
use App\Models\Service;

class Invoice extends Model
{
    protected $fillable = [
        'type',
        'tenant_id',       // FK → users.id
        'provider_id',       // FK → users.id
        'property_id',     // FK → properties.id (nullable)
        'invoice_number',
        'services',
        'rent_month',
        'amount_due',
        'due_date',
        'status'
    ];

    // Relationships

    // Tenant who owes the invoice
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    // Property tied to the invoice (if any)
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    // Optional: convenience attribute for frontend
    public function getInvoiceTitleAttribute()
    {
        return $this->property ? $this->property->name : ($this->service ? $this->service->name : 'N/A');
    }

    public static function generateInvoiceNumber()
    {
        $lastId = self::max('id') ?? 0;

        return 'INV-' . date('Y') . '-' . str_pad(
            $lastId + 1,
            6,
            '0',
            STR_PAD_LEFT
        );
    }    
}
