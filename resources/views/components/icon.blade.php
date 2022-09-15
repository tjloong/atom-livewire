@if ($svg)
    <i 
        {{ $attributes->class(['inline-block'])->except(['name', 'size']) }} 
        style="width: {{ $size }}px; height: {{ $size }}px;"
    >
        {!! $svg !!}
    </i>
@endif
