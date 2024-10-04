@php
$attrs = $attributes
    ->merge([
        'x-init' => "\$nextTick(() => \$el.querySelector('input[autofocus]')?.focus())",
        'wire:submit.prevent' => $attributes->submit() ? null : 'submit',
    ])
    ->except('class')
    ;
@endphp

<form {{ $attrs }} class="group/form relative" data-atom-form>
    <div class="absolute inset-0 z-1 hidden group-[.is-loading]/form:block">
        <div class="absolute top-4 right-4 text-primary">
            <x-spinner size="20"/>
        </div>
    </div>

    <div class="{{ $attributes->get('class', 'space-y-6') }}">
        {{ $slot }}
    </div>
</form>
