<figure {{ $attributes->class([
    'relative rounded-lg shadow bg-gray-200 overflow-hidden',
    $attributes->get('class'),
    $square ? 'pt-[100%]' : 'pt-[60%]',
])->except(['file', 'url','icon','youtube','video', 'square']) }}>
    <div class="absolute inset-0">
        @if ($video) <video class="w-full h-full object-cover"><source src="{{ $video }}"></video>
        @elseif ($youtube) <img src="{{ $youtube }}" class="w-full h-full object-cover">
        @elseif ($image) <img src="{{ $image }}" class="w-full h-full object-cover">
        @endif
    </div>

    @if ($youtube || $video || $icon)
        @php $width = $square ? '25%' : '15%' @endphp
        <div class="absolute inset-0 flex items-center justify-center">
            @if ($youtube) <x-icon name="youtube" class="text-red-500" style="width: {{ $width }};"/>
            @elseif ($video) <x-icon name="play" class="text-blue-500" style="width: {{ $width }};"/>
            @else <x-icon :name="$icon" style="width: {{ $width }};"/>
            @endif
        </div>
    @endif

    @isset($buttons)
        <div class="absolute top-0 right-0 left-0 bg-gradient-to-b from-gray-500 to-transparent pt-2 px-2 pb-4">
            <div class="flex items-center gap-2 justify-end">
                {{ $buttons }}
            </div>
        </div>
    @endisset
</figure>
