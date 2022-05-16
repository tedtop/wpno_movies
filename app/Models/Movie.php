<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Movie extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get movie, associated genres, and associated languages
     */
    public function getMovieData(Movie $movie)
    {
        // comma concat unique genres list for table
        $genresList = [];
        foreach ($movie->genres as $mg) {
            $genresList[] = $mg->genre;
        }
        // hack because the model saves duplicate n-to-n data
        $genresList = array_unique($genresList);
        $movie->Genre = implode(', ', $genresList);

        // comma concat unique languages list for table
        $languagesList = [];
        foreach ($movie->languages as $ml) {
            $languagesList[] = $ml->language;
        }
        // hack because the model saves duplicate n-to-n data
        $languagesList = array_unique($languagesList);
        $movie->Language = implode(', ', $languagesList);

        return [
            'id' => $movie->id,
            'Poster' => $movie->Poster,
            'Title' => $movie->Title,
            'Genre' => $movie->Genre,
            'Language' => $movie->Language,
            'deleted_at' => $movie->deleted_at,
        ];
    }

    /**
     * Insert movie, associated genres, and associated languages
     */
    static public function saveMovieDataFromOMDb($json)
    {
        $movie = Auth::user()->account->movies()->updateOrCreate([
            'Title' => $json['Title'],
            'Year' => $json['Year'],
            'Rated' => $json['Rated'],
            'Released' => $json['Released'],
            'Runtime' => $json['Runtime'],
            // 'Genre' => $json['Genre'],
            'Director' => $json['Director'],
            'Writer' => $json['Writer'],
            'Actors' => $json['Actors'],
            'Plot' => $json['Plot'],
            // 'Language' => $json['Language'],
            'Country' => $json['Country'],
            'Awards' => $json['Awards'],
            'Poster' => $json['Poster'],
            'Ratings' => serialize($json['Ratings']),
            'Metascore' => $json['Metascore'],
            'imdbRating' => $json['imdbRating'],
            'imdbVotes' => $json['imdbVotes'],
            'imdbID' => $json['imdbID'],
            'Type' => $json['Type'],
            'DVD' => $json['DVD'] ?? 'N/A',
            'BoxOffice' => $json['BoxOffice'] ?? 'N/A',
            'Production' => $json['Production'] ?? 'N/A',
            'Website' => $json['Website'] ?? 'N/A',
            'Response' => $json['Response'],
            'json_response' => serialize($json)
        ]);

        // Save & associate genres
        foreach (explode(',', $json['Genre']) as $genre) {
            $res = Genre::updateOrCreate(['genre' => trim($genre)]);
            $movie->genres()->save($res);
        }

        // Save & associate languages
        foreach (explode(',', $json['Language']) as $language) {
            $res = Language::updateOrCreate(['language' => trim($language)]);
            $movie->languages()->save($res);
        }
    }

    /**
     * The genres that belong to the movie.
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genres_movies');
    }

    /**
     * The languages that belong to the movie.
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'languages_movies');
    }

    public function scopeOrderByTitle($query)
    {
        $query->orderBy('Title');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('Title', 'like', '%' . $search . '%');
        })->when($filters['genre'] ?? null, function ($query, $genre) {
            $query->whereHas('genres', function ($query) use ($genre) {
                $query->where('genre', $genre);
            });
        })->when($filters['language'] ?? null, function ($query, $language) {
            $query->whereHas('languages', function ($query) use ($language) {
                $query->where('language', $language);
            });
        });
    }
}
