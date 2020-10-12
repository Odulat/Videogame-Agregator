<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GamesController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource
     *
     * @param string $slug
     *
     * @return Application|Factory|View
     */
    public function show($slug)
    {
        $game = Cache::remember('show', 7, function () use ($slug) {
            return Http::withHeaders(config('services.igdb'))
                ->withOptions([
                    'body' => "
                        fields name, cover.url, first_release_date, popularity, rating, platforms.abbreviation, slug, involved_companies.company.name, genres.name, aggregated_rating, websites.*, videos.*, screenshots.*, similar_games.cover.url, similar_games.name, similar_games.rating, similar_games.platforms.abbreviation, similar_games.slug, summary;
                        where slug = \"{$slug}\";
                    "
                ])->get('https://api-v3.igdb.com/games')
                ->json();
        });

        $game = collect($game)->map(function ($oneGame) {
            return collect($oneGame)->merge([
                'involved_companies' => collect($oneGame['involved_companies'])->pluck('company')->pluck('name')->whereNotNull()->implode(', '),
                'genres' => collect($oneGame['genres'])->pluck('name')->whereNotNull()->implode(', '),
                'platforms' => collect($oneGame['platforms'])->pluck('abbreviation')->whereNotNull()->implode(', '),
                'similar_games' => collect($oneGame['similar_games'])->map(function ($platform) {
                    return collect($platform)->merge([
                       'platforms' => array_key_exists('platforms', $platform)
                            ? collect($platform['platforms'])->pluck('abbreviation')->whereNotNull()->implode(', ')
                            : null
                    ]);
                })->take(6),
                'social' => [
                    'website' => array_key_exists('websites', $oneGame)
                        ? collect($oneGame['websites'])->first()
                        : null,
                    'facebook' => array_key_exists('websites', $oneGame)
                        ? collect($oneGame['websites'])->filter(function ($website) {
                            return Str::contains($website['url'], 'facebook');
                        })->first()
                        : null,
                    'instagram' => array_key_exists('websites', $oneGame)
                        ? collect($oneGame['websites'])->filter(function ($website) {
                            return Str::contains($website['url'], 'instagram');
                        })->first()
                        : null,
                    'twitter' => array_key_exists('websites', $oneGame)
                        ? collect($oneGame['websites'])->filter(function ($website) {
                            return Str::contains($website['url'], 'twitter');
                        })->first()
                        : null
                ]
            ]);
        })->toArray();

        abort_if(!$game, 404);

        return view('show', [
            'game' => $game[0]
        ]);
    }


    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }
}
