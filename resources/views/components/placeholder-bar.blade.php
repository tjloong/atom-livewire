<div 
    style="width: {{ $width }}; height: {{ $height ?? '10px' }};"
    {{ $attributes->class([
        'rounded-xl',
        $attributes->get('class', 'bg-gray-300'),
    ])->except('size') }}
></div>
