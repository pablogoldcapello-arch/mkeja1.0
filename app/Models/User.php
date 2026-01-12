<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Property;
use App\Models\SupportTicket;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'profile_photo_url',
        'phone',
        'role',
        'property_count',
        'assigned_properties',
        'skills',
        'is_email_verified',
        '2fa_enabled',
        'status',
        'dob',
        'gender',
        'address',
        'city',
        'county',
        'postal_code',
        'last_login',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_email_verified' => 'boolean',
        '2fa_enabled' => 'boolean',
        'assigned_properties' => 'array',
        'skills' => 'array',
        'last_login' => 'datetime',
    ];    

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }    

    public function properties()
    {
        return $this->belongsToMany(
            Property::class,
            'caretaker_property', // pivot table name
            'caretaker_id',       // foreign key on pivot for User
            'property_id'         // foreign key on pivot for Property
        )->withTimestamps();
    }

    public function leases()
    {
        return $this->hasMany(Lease::class, 'tenant_id');
    }

    // Tickets created by this user
    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }

    // Tickets assigned to this user (e.g., caretaker/service provider)
    public function assignedTickets()
    {
        return $this->hasMany(SupportTicket::class, 'assigned_to');
    }



}
