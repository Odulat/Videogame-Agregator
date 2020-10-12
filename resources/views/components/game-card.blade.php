<div class="game mt-8 mx-auto lg:mx-0">
    <div class="relative inline-block">
        @if(array_key_exists('cover', $game))
            <a href="{{ route('games.show', $game['slug']) }}">
                <img src="{{ Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) }}" alt="game cover" class="hover:opacity-75 transition ease-in-out duration-150">
            </a>
        @else
            <a href="{{ route('games.show', $game['slug']) }}">
                <div class="bg-gray-800 w-42 h-56"></div>
            </a>
        @endif
        @if(isset($game['rating']))
            <div id="{{ $game['slug'] }}" class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full" style="right: -20px; bottom: -20px">
            </div>
            @push('scripts')
                @include('_rating', [
                    'slug' => $game['slug'],
                    'rating' => array_key_exists('rating', $game)
                        ? round($game['rating'])
                        : '0',
                    'event' => null
                ])
            @endpush
        @endif
    </div>
    <a href="{{ route('games.show', $game['slug']) }}" class="block text-base font-semibold leading-tight hover:text-gray-400 mt-8">
        @if(\Illuminate\Support\Str::length($game['name']) > 35)
            {{ \Illuminate\Support\Str::of($game['name'])->substr(0, 35). "..."}}
        @else
            {{ $game['name'] }}
        @endif
    </a>
    <div class="text-gray-400 mt-1">
        {{ $game['platforms'] }}
    </div>
</div>
