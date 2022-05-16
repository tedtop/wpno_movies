<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The movies that belong to the genre.
     */
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'languages_movies')->withPivot('language');
    }
}
