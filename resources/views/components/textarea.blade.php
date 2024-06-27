@php
$placeholder = $attributes->get('placeholder');
$transparent = $attributes->get('transparent');
@endphp

<x-input class="w-full px-3 py-1.5" {{ $attributes->except('class') }}>
    <textarea
        placeholder="{!! tr($placeholder) !!}"
        {{ $attributes
        ->merge(['rows' => 3])
        ->class(array_filter([
            'appearance-none bg-transparent w-full disabled:resize-none read-only:resize-none',
            $transparent ? 'resize-none hover:resize focus:resize' : null,
        ]))
        ->except(['label', 'field', 'for', 'placeholder']) }}>
    </textarea>
</x-input>
