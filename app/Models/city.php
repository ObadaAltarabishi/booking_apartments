<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\apartments;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class city extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $table = 'city';

    protected $fillable = [
        'name'
        
    ];

    public function apartments(): HasMany
    {
        return $this->hasMany(Apartments::class);
    }


}