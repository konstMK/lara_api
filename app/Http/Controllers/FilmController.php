<?php

namespace App\Http\Controllers;


use App\Film;
use App\Services\RestClientInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FilmController extends Controller
{
    private $restClient;

    public function __construct(RestClientInterface $restClient)
    {
        $this->restClient = $restClient;
    }

    public function search($film)
    {
        /** @var DB $filmsInDatabase */
        $filmsInDatabase = DB::select(
            "SELECT * FROM films
            WHERE title LIKE '%".$film."%' LIMIT 1"
        );

        if (!empty($filmsInDatabase)) {
            $film = $filmsInDatabase[0];
            unset($film->id);
            unset($film->created_at);
            unset($film->updated_at);
            return response()->json($film);

        }
        $url = sprintf(
            'http://www.omdbapi.com/?apikey=%s&t=%s',
            'dfb1f0ae', $film);
        $result = $this->restClient->get($url);

        if (!isset($result['Title'])) {
            return response()->json([
                'error' => 'Film not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $result = [
            'title' => $result['Title'],
            'director' => $result['Director'],
            'description' => $result['Plot'],
            'imdb_id' => $result['imdbID'],
        ];


        Film::create($result);

        return response()->json($result);
    }
}