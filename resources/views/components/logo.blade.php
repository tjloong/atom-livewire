<figure 
    {{ $attributes->except('name') }}
    @if ($size) style="width: {{ $size }}px; height: {{ $size }}px;" @endif
>
    @if (str($src)->startsWith('<svg'))
        {!! $src !!}
    @elseif ($src = $attributes->get('src') ?? $src)
        @if ($href = $attributes->get('href'))
            <a href="{{ $href }}">
                <img 
                    src="{{ $src }}" 
                    class="w-full h-full object-contain" 
                    width="100" 
                    height="100" 
                    alt="{{ $attributes->get('alt') ?? str($name)->headline() }}"
                >
            </a>
        @else
            <img 
                src="{{ $src }}" 
                class="w-full h-full object-contain" 
                width="100" 
                height="100" 
                alt="{{ $attributes->get('alt') ?? str($name)->headline() }}"
            >
        @endif
    @endif
</figure>