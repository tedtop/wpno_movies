<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Language;
use App\Models\Movie;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class MoviesController extends Controller
{
    public function index()
    {
        // @TODO: This whole section & store() method could/should be moved to its own service provider
        // Get movie from OMDb API "By Title" with t param (returns only one full result)
        // "By Search" returns many movies per search string, but without genre & language
        $title = Request::get('search');
        // Let's only look for titles over 3 chars; though there are many single char movie names
        if (strlen($title) >= 3) {
            $response = Http::get('http://www.omdbapi.com/?apikey=' . env('OMDB_API_KEY') . '&type=movie&t=' . $title);
            $this->store($response->json()); // saves the single "By Title" result
        }

        // Render the view
        return Inertia::render('Movies/Index', [
            'filters' => Request::all('search', 'genre', 'language'),
            'movies' => Auth::user()->account->movies()
                ->with('genres')
                ->with('languages')
                ->orderByTitle()
                ->filter(Request::only('search', 'genre', 'language'))
                ->get()
                ->transform(
                    function ($movie) {
                        return $movie->getMovieData($movie);
                    }
                ),
            'genres' => Genre::all(),
            'languages' => Language::all()
        ]);
    }

    // Saves the single "By Title" result
    public function store($json)
    {
        if ($json['Response'] == 'True') {
            Movie::saveMovieDataFromOMDb($json);
        }
        return Redirect::route('movies'); // ->with('success', 'New movie added to local storage.');
    }

    /**
     * @TODO: Build a movie detail view with option to delete/restore
     */
    public function detail(Movie $movie)
    {
        dd($movie->toArray());
    }

    /**
     * @TODO: For future use when building movie detail view
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return Redirect::back()->with('success', 'Movie deleted.');
    }

    /**
     * @TODO: For future use when building movie detail view
     */
    public function restore(Movie $movie)
    {
        $movie->restore();

        return Redirect::back()->with('success', 'Movie restored.');
    }
}
