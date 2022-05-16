<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The genres that belong to the movie.
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genres_movies')->withPivot('genre');
    }
}
