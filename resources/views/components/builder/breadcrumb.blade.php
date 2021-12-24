<div class="flex flex-wrap items-center gap-2 py-2">
@foreach ($links as $i => $link)
    @if ($href = $link['href'] ?? null)
        <a href="{{ $href }}" class="text-gray-500 hover:text-gray-800 flex items-center justify-center">
            @if ($link['label'] === 'Home') <x-icon name="home" type="solid" size="20px"/>
            @else {{ $link['label'] }}
            @endif
        </a>
    @else
        <div class="text-gray-500">
            @if ($link['label'] === 'Home') <x-icon name="home"/>
            @else {{ $link['label'] }}
            @endif
        </div>
    @endif

    @if ($i !== count($links) - 1)
        <div class="flex items-center justify-center text-gray-500">
            <x-icon name="chevron-right"/>
        </div>
    @endif
@endforeach
</div>