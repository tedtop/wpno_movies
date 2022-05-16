<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class MoviesController extends Controller
{
    public function index()
    {
        // Get movie from OMDb API "By Title" with t param (returns only one full result)
        // "By Search" returns many movies per search string, but without genre & language
        $title = Request::get('search');
        $response = Http::get('http://www.omdbapi.com/?apikey=b07b195f&type=movie&t=' . $title);
        $this->store($response->json());

        // Render the view
        return Inertia::render('Movies/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'movies' => Auth::user()->account->movies()
                ->orderByTitle()
                ->filter(Request::only('search'))
                ->get()
                ->transform(fn ($movie) => [
                    'id' => $movie->id,
                    'Poster' => $movie->Poster,
                    'Title' => $movie->Title,
                    'Genre' => $movie->Genre,
                    'Language' => $movie->Language,
                    'deleted_at' => $movie->deleted_at,
                ]),
        ]);
    }

    public function store($json)
    {
        if ($json['Response'] == 'True') {
            Auth::user()->account->movies()->updateOrCreate([
                'Title' => $json['Title'],
                'Year' => $json['Year'],
                'Rated' => $json['Rated'],
                'Released' => $json['Released'],
                'Runtime' => $json['Runtime'],
                'Genre' => $json['Genre'],
                'Director' => $json['Director'],
                'Writer' => $json['Writer'],
                'Actors' => $json['Actors'],
                'Plot' => $json['Plot'],
                'Language' => $json['Language'],
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
        }

        return Redirect::route('movies'); // ->with('success', 'New movie added to local storage.');
    }

    public function detail(Movie $movie)
    {
        dd($movie);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return Redirect::back()->with('success', 'Movie deleted.');
    }

    public function restore(Movie $movie)
    {
        $movie->restore();

        return Redirect::back()->with('success', 'Movie restored.');
    }
}
