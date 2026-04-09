<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDocument extends Model
{
    // Table name (optional if it matches Laravel convention)
    protected $table = 'user_documents';

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'type',          // e.g., 'landlord_agreement', 'tenant_agreement'
        'file_name',     // stored path in storage
        'original_name', // original file name
        'file_url',      // public URL to access
        'status',        // e.g., 'pending', 'approved'
    ];

    // Relationship to user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}