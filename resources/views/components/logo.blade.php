<figure {{ $attributes->except('name') }}>
    @if (str($src)->startsWith('<svg'))
        {!! $src !!}
    @elseif ($src)
        <img 
            src="{{ $src }}" 
            class="w-full h-full object-contain" 
            width="100" 
            height="100" 
            alt="{{ $attributes->get('alt') ?? str($name)->headline() }}"
        >
    @endif
</figure>