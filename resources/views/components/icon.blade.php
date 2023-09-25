@if (isset($svg) && $svg->isNotEmpty())
    <i {{ $attributes
        ->class(['inline-block'])
        ->merge(['style' => 'width: '.$size.'px; height:'.$size.'px'])
        ->except(['name', 'size']) }}>
        {{ $svg }}
    </i>
@elseif (str($icon)->startsWith('<svg '))
    <i {{ $attributes
        ->class(['inline-block'])
        ->merge(['style' => 'width: '.$size.'px; height:'.$size.'px'])
        ->except(['name', 'size']) }}>
        {!! $icon !!}
    </i>
@else
    <i {{ $attributes->merge(['class' => $icon]) }}></i>
@endif
