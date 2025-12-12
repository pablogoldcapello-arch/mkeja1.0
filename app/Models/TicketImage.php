<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SupportTicket;

class TicketImage extends Model
{
    protected $fillable = [
        'ticket_id', // optional if you always set via relationship
        'name',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class);
    }    
}
