@php
$placeholder = $attributes->get('placeholder');
@endphp

<x-form.field {{ $attributes->except('class') }}>
    <textarea placeholder="{!! tr($placeholder) !!}" {{ $attributes->class([
        $attributes->get('class', 'form-input w-full')
    ])->merge(['rows' => 3])->except('placeholder') }}>
    </textarea>
</x-form.field>
