@aware(['size', 'invalid', 'placeholder'])

@php
$classes = $attributes->classes()
    ->add('w-full py-2 pl-[9.5rem] pr-10 text-zinc-700')
    ->add('border border-zinc-200 border-b-zinc-300/80 rounded-lg shadow-sm bg-white')
    ->add('focus:outline-none focus:border-primary group-focus/input:border-primary hover:border-primary-300')
    ->add($invalid ? 'border-red-400' : 'group-has-[[data-atom-error]]/field:border-red-400')
    ->add($size === 'sm' ? 'h-8 text-sm' : 'h-10')
    ;

$attrs = $attributes
    ->class($classes)
    ->whereDoesntStartWith('wire:model')
    ->whereDoesntStartWith('x-model')
    ->except(['size', 'placeholder', 'field', 'error'])
    ;
@endphp

<div
    x-data="tel({
        code: {{ js($attributes->get('code', '+60')) }},
        @if ($attributes->wire('model')->value())
        value: @entangle($attributes->wire('model')),
        @endif
    })"
    class="group/input relative w-full block">
    <input
        type="hidden"
        x-ref="hidden"
        {{ $attributes->whereStartsWith('wire:model') }}
        {{ $attributes->whereStartsWith('x-model') }}>

    <div class="absolute top-0 bottom-0 left-0 w-[9rem] flex items-center gap-2">
        <div class="relative w-full">
            <select
                x-ref="options"
                x-model="code"
                x-on:change="format()"
                x-on:input.stop
                data-atom-input-tel-country
                class="appearance-none bg-transparent pl-3 pr-6 w-full focus:outline-none">
                @foreach (atom()->country()->sortBy('iso_code') as $country)
                    <option value="{{ get($country, 'dial_code') }}">
                        {{ get($country, 'iso_code') }} ({{ get($country, 'dial_code') }})
                    </option>
                @endforeach
            </select>

            <div class="pointer-events-none absolute top-0 bottom-0 right-0 pr-2 flex items-center justify-center">
                <atom:icon dropdown/>
            </div>
        </div>
    </div>

    <input
        type="tel"
        x-model="tel"
        x-on:input.stop="format()"
        placeholder="{{ t($placeholder) }}"
        {{ $attrs }}>

    <div class="absolute top-0 right-0 bottom-0 flex items-center justify-center text-muted-more px-3">
        <atom:icon phone/>
    </div>
</div>
