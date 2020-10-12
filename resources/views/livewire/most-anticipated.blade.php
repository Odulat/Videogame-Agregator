<div wire:init="loadMostAnticipated" class="most-anticipated-container space-y-10 mt-8">
    @forelse($mostAnticipated as $game)
        <x-game-card-small :game="$game"></x-game-card-small>
    @empty
        @foreach(range(1, 4) as $game)
            <div class="game flex">
                <div class="bg-gray-800 w-16 h-22 flex-none"></div>
                <div class="ml-4">
                    <div class="text-transparent bg-gray-800 mt-2 rounded leading-tight">Title goes here</div>
                    <div class="text-transparent bg-gray-800 rounded inline-block text-sm mt-2">Sept 16, 2020</div>
                </div>
            </div>
        @endforeach
    @endforelse
</div>
