<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Movie;

class Character extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'picture_path',
        'description',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'character_movie');
    }
}

