@if ($icon || isset($svg))
    <i 
        {{ $attributes->class(['inline-block'])->except(['name', 'size']) }} 
        style="width: {{ $size }}px; height: {{ $size }}px;"
    >
        @if (isset($svg) && $svg->isNotEmpty()) {{ $svg }}
        @else {!! $icon !!}
        @endisset
    </i>
@endif
