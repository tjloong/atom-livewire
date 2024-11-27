@php
$name = $attributes->get('name');
$label = $attributes->get('label');
$caption = $attributes->get('caption');
$inline = $attributes->get('inline');
$variant = $attributes->get('variant');

$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$required = $attributes->get('required') ?? $this->form['required'][$field] ?? false;
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;

$classes = $attributes->classes()->add('space-y-2');
$attrs = $attributes->class($classes)->except(['variant', 'name']);
@endphp

@if ($label || $caption)
    <atom:_input.field
        :label="$label"
        :caption="$caption"
        :inline="$inline"
        :required="$required"
        :error="$error">
        <atom:radio.group :attributes="$attributes->except(['label', 'caption', 'error', 'inline'])">
            {{ $slot }}
        </atom:radio.group>
    </atom:_input.field>
@else
    <div
        x-data="{
            radioValue: @if ($attributes->wire('model')->value()) @entangle($attributes->wire('model')) @else null @endif,
            groupName: @js($field ?? $attributes->get('name') ?? $attributes->getLike('x-model*')),
        }"
        x-modelable="radioValue"
        {{ $attrs->whereDoesntStartWith('wire:model') }}>
        {{ $slot }}
    </div>
@endif
