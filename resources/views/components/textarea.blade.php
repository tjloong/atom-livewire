@php
$label = $attributes->get('label');
$placeholder = $attributes->get('placeholder');
$except = ['label', 'placeholder'];
@endphp

<x-input class="w-full pt-2" {{ $attributes->except('class') }}>
    <textarea
        class="appearance-none bg-transparent w-full disabled:resize-none"
        placeholder="{!! tr($placeholder) !!}"
        {{ $attributes->merge(['rows' => 3])->except($except) }}>
    </textarea>
</x-input>
