@if (str($icon)->startsWith('<svg '))
    <i {{ $attributes
        ->class(['inline-block'])
        ->merge(['style' => 'width: '.$size.'px; height:'.$size.'px'])
        ->except(['name', 'size']) }} 
    >
        @if (isset($svg) && $svg->isNotEmpty()) {{ $svg }}
        @else {!! $icon !!}
        @endisset
    </i>
@else
    <i class="{{ $icon }}"></i>
@endif
