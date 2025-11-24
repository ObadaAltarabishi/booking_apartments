<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class apartments extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'apartments';

    protected $fillable = [
        'price',
        'title',
        'description',
        'status',
        'images',
        'user_id',
        'city_id',
    ];


    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'images' => 'array',
        'status' => 'string',
    ];

    /**
     * Get the user that owns the apartment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the city that the apartment belongs to.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
   
   
    public function reviews(): HasMany
{
    return $this->hasMany(Reviews::class, 'apartments_id');
}

public function bookings(): HasMany
{
    return $this->hasMany(Booking::class, 'apartments_id');
}
}