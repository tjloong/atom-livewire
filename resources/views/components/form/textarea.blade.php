@php
$autoresize = $attributes->get('autoresize', true);
$placeholder = $attributes->get('placeholder');
@endphp

<x-form.field {{ $attributes->except('class') }}>
    <textarea placeholder="{!! tr($placeholder) !!}" {{ $attributes->class([
        $autoresize ? 'resize-none overflow-hidden' : '',
        $attributes->get('class', 'form-input w-full')
    ])->except('placeholder') }} @if ($autoresize) x-textarea.autoresize @endif>
    </textarea>
</x-form.field>
