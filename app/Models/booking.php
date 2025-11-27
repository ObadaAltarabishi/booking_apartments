<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'startDate',
        'endDate',
        'user_id',
        'apartments_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'startDate' => 'date',
        'endDate' => 'date',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the apartment that the booking belongs to.
     */
    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartments::class, 'apartments_id');
    }
    // In Booking model
public function getNightsAttribute(): int
{
    return $this->startDate->diffInDays($this->endDate);
}
}