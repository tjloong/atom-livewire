<div class="bg-white rounded-md drop-shadow overflow-hidden transition-all hover:drop-shadow-lg">
    <div class="relative pt-[60%] bg-gray-200 overflow-hidden">
        @if ($attributes->get('image'))
            <figure class="absolute inset-0">
                <img
                    src="{{ $attributes->get('image') }}"
                    alt="{{ $attributes->get('alt') }}"
                    class="w-full h-full object-cover transition-all duration-500 hover:transform hover:scale-125"
                >
            </figure>
        @endif
    </div>

    {{ $slot }}
</div>
