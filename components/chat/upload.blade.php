@php
$settings = $attributes->get('settings');
@endphp

<div>
    <input type="file"
        x-on:click.stop=""
        x-on:change="attach($event.target.files)"
        x-bind:multiple="upload.multiple"
        x-bind:accept="upload.accept"
        class="hidden">

    <button
        type="button"
        x-tooltip="{{ js(t('attach')) }}"
        x-on:click="$el.parentNode.querySelector('input').click()"
        class="p-1.5 flex items-center justify-center">
        <atom:icon attach size="15"/>
    </button>
</div>
