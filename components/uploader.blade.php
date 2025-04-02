@php
$max = $attributes->get('max') ?? config('atom.max_upload_size');
$accept = $attributes->get('accept');
$multiple = $attributes->get('multiple');
$visibility = $attributes->get('visibility');
$attrs = $attributes->except(['max', 'accept', 'multiple', 'visibility']);
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
    visibility: @js($visibility),
})"
x-modelable="value"
{{ $attrs }}>
    <input 
    type="file"
    x-ref="input"
    x-on:change="read(Array.from($event.target.files))"
    x-on:input.stop
    x-on:click.stop
    accept="{{ $accept }}"
    @if ($multiple)
    multiple
    @endif
    class="hidden">

    <div x-ref="trigger">
        {{ $slot }}
    </div>
</div>
