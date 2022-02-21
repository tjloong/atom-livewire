@if ($attributes->has('figure'))
    <figure class="absolute inset-0">
        <img
            src="{{ $image['url'] }}"
            alt="{{ $image['alt'] }}"
            class="w-full h-full object-cover transition-all duration-500 hover:transform hover:scale-125"
        >
    </figure>

@elseif ($href)
    <a 
        href="{{ $href }}"
        class="block bg-white text-gray-800 rounded-md drop-shadow overflow-hidden transition-all hover:drop-shadow-lg"
    >
        <div class="relative pt-[60%] bg-gray-200 overflow-hidden">
            @if ($image['url']) <x-card figure :image="$image"/> @endif
        </div>
    
        {{ $slot }}
    </a>
@else
    <div class="bg-white rounded-md drop-shadow overflow-hidden transition-all hover:drop-shadow-lg">
        <div class="relative pt-[60%] bg-gray-200 overflow-hidden">
            @if ($image['url']) <x-card figure :image="$image"/> @endif
        </div>

        {{ $slot }}
    </div>

@endif
