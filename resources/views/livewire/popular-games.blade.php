<div wire:init="loadPopularGames" class="popular-games text-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 xl:grid-cols-6 gap-12 border-b border-gray-800 pb-16">
    @forelse($popularGames as $game)
        <x-game-card :game="$game"></x-game-card>
    @empty
        @foreach(range(1, 12) as $game)
            <div class="game mt-8 mx-auto lg:mx-0">
                <div class="relative inline-block">
                    <div class="bg-gray-800 w-42 h-56"></div>
                </div>
                <div class="block text-transparent text-lg bg-gray-800 rounded leading-tight mt-8">Title goes here</div>
                <div class="text-transparent bg-gray-800 rounded inline-block mt-1">
                    PS4, PC, Switch
                </div>
            </div>
        @endforeach
    @endforelse
</div>

@push('scripts')
    @include('_rating', [
        'event' => 'gameWithRatingAdded'
    ])
@endpush
