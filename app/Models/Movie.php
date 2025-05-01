<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Character;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'classification',
        'release_date',
        'review',
        'season',
        'poster_path',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function characters()
    {
        return $this->belongsToMany(Character::class, 'character_movie');
    }

    public function scopePast($query)
    {
        return $query->where('release_date', '<', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('release_date', '>', now());
    }

    public function scopeRecent($query)
    {
        return $query->whereBetween('release_date', [now()->subMonths(3), now()]);
    }
    
}
