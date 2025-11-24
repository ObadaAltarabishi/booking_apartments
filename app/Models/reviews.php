<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class reviews extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'reviews';

    protected $fillable = [
        'count',
        'reviewsAll',
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
}
