@if ($family === 'fa')
    <i {{ $attributes->class(['inline-block'])->except(['name', 'size']) }} style="width: {{ $size }}px; height: {{ $size }}px;">
        {!! $icon !!}
    </i>

@elseif ($family === 'box')
    <i {{ $attributes->class(['bx', $icon, $style ? null : $size])->except(['name', 'size']) }} {!! $style !!}></i>
@endif
