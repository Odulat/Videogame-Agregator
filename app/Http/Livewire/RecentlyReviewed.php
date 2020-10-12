<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class RecentlyReviewed extends Component
{
    public $recentlyReviewed = [];

    public function loadRecentlyReviewed()
    {
        $before = Carbon::now()->subMonths(2)->timestamp;
        $current = Carbon::now()->timestamp;

        $this->recentlyReviewed = Cache::remember('recently-reviewed', 7, function () use ($before, $current) {
            sleep(3);
            return Http::withHeaders(config('services.igdb'))
                ->withOptions([
                    'body' => "
                    fields name, cover.url, first_release_date, popularity, platforms.abbreviation, rating, rating_count,
                        summary, slug;
                    where platforms = (48,49,130,6)
                    & (first_release_date >= {$before}
                    & first_release_date < {$current}
                    & rating_count > 5);
                    sort popularity desc;
                    limit 3;
                "
                ])->get('https://api-v3.igdb.com/games')
                ->json();
        });

        $this->recentlyReviewed = collect($this->recentlyReviewed)->map(function ($game) {
            return collect($game)->merge([
                'platforms' => collect($game['platforms'])->pluck('abbreviation')->whereNotNull()->implode(', ')
            ]);
        })->toArray();

        collect($this->recentlyReviewed)->filter(function ($game) {
            if (array_key_exists('rating', $game)) {
                return $game['rating'];
            }
            else return null;
        })->each(function ($game) {
            $this->emit('reviewGameWithRatingAdded', [
                'slug' => 'review_'.$game['slug'],
                'rating' => $game['rating'] / 100
            ]);
        });

    }

    public function render()
    {
        return view('livewire.recently-reviewed');
    }
}
