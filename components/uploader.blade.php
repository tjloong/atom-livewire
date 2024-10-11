@php
$max = $attributes->get('max') ?? config('atom.max_upload_size');
$accept = $attributes->get('accept');
$multiple = $attributes->get('multiple');
$attrs = $attributes->except(['max', 'accept', 'multiple']);
@endphp

<div
    x-cloak
    x-data="uploader({
        @if ($attributes->wire('model')->value())
        value: @entangle($attributes->wire('model')),
        @endif
        max: @js($max),
        accept: @js($accept),
        multiple: @js($multiple),
    })"
    x-modelable="value"
    {{ $attrs }}>
    <input 
        type="file"
        x-ref="input"
        x-on:change="read(Array.from($event.target.files))"
        x-on:input.stop
        accept="{{ $accept }}"
        @if ($multiple)
        multiple
        @endif
        class="hidden">

    <div x-ref="trigger">
        {{ $slot }}
    </div>
</div>
