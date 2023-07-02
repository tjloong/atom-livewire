@props([
    'field' => $attributes->get('field'),
    'model' => $attributes->wire('model')->value(),
])

@php $label = data_get($field, 'label') @endphp
@php $type = data_get($field, 'type') @endphp
@php $required = data_get($field, 'required') @endphp

<x-form.field :label="$label" :required="$required">
    @if (in_array($type, ['text', 'number', 'email']))
        <x-dynamic-component :component="'form.'.$type"
            wire:model.defer="{{ $model }}" 
            :label="false"
            :required="$required"
            :placeholder="data_get($field, 'placeholder')"
            step=".01"
        />
    @elseif (in_array($type, ['dropdown', 'multiple']))
        <x-form.select wire:model="{{ $model }}"
            :label="false"
            :multiple="$type === 'multiple'"
            :options="data_get($field, 'options')"
            :placeholder="data_get($field, 'placeholder', 'Select '.$label)"
        />
    @elseif ($type === 'boolean')
        <x-form.select wire:model="{{ $model }}"
            :label="false"
            :options="['Yes', 'No']"
            :placeholder="data_get($field, 'placeholder', 'Select '.$label)"
        />
    @elseif ($type === 'date')
        <x-form.date wire:model="{{ $model }}" 
            :label="false" 
            :required="$required"
            :placeholder="data_get($field, 'placeholder', 'Select '.$label)"
        />
    @endif
</x-form.field>