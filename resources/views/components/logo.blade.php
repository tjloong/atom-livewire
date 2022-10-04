<figure 
    {{ $attributes->except('name') }}
    @if ($size) style="width: {{ $size }}px; height: {{ $size }}px;" @endif
>
    @if (str($src)->startsWith('<svg'))
        {!! $src !!}
    @elseif ($attributes->has('src'))
        <img 
            src="{{ $attributes->get('src') }}" 
            class="w-full h-full object-contain" 
            width="100" 
            height="100" 
            alt="{{ $attributes->get('alt') ?? str($name)->headline() }}"
        >
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