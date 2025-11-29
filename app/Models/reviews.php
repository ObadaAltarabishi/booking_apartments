<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reviews extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'count',
        'reviewsAll', // This stores the sum of all ratings
        'user_id',
        'apartments_id',
    ];

    protected $casts = [
        'reviewsAll' => 'float',
        'count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartments::class, 'apartments_id');
    }


    // Add a new rating and update the average
    public function addRating(int $rating): void
    {
        $this->reviewsAll += $rating;
        $this->save();
    }

}