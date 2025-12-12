<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TicketImage;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_to',
        'category',
        'priority',
        'status',
        'description',
        'resolution',
        'status'
    ];   
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    } 
    
    public function images(){
        return $this->hasMany(TicketImage::class, 'ticket_id');
    }    
}
