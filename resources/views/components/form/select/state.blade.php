@php
    $label = $attributes->get('label', 'common.label.state');
    $placeholder = $attributes->get('placeholder', 'common.label.select-state');
    $country = $attributes->get('country');
    $options = collect(countries($country.'.states'))->map(fn($opt) => [
        'value' => data_get($opt, 'name'),
        'label' => data_get($opt, 'name')
    ])->sort()->values()->all();
@endphp

<x-form.field {{ $attributes }}>
    @if ($options)
        <x-form.select :label="false" :options="$options" wire:key="{{ uniqid() }}"
            :placeholder="$placeholder"
            {{ $attributes->except(['label', 'options', 'placeholder']) }}/>
    @else
        <x-form.select :label="false" :options="[]"
            :placeholder="$placeholder"
            {{ $attributes->except(['label', 'options', 'placeholder']) }}/>
    @endif
</x-form.field>
