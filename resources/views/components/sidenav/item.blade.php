@php
    $href = $attributes->get('href');
    $label = $attributes->get('label');
    $badge = $attributes->get('badge');
    $count = $attributes->get('count');
    $disabled = $attributes->get('disabled');
    $value = $attributes->get('value') ?? $attributes->get('name') ?? $attributes->get('slug');
@endphp

@if ($disabled)
    <div class="flex items-center gap-3 py-1.5 px-3 -mx-2 opacity-50 pointer-event-none">
        @if ($icon = $attributes->get('icon'))
            <div class="shrink-0 w-4 flex text-gray-400">
                <x-icon :name="$icon" class="m-auto"/>
            </div>
        @endif

        <div class="grow text-gray-600 font-medium">
            {{ tr($label) }}
        </div>
    </div>
@else
    <div 
        x-cloak
        x-data="{
            href: @js($href),
            value: @js($value),
            label: @js(tr($label)),
            get active () {
                return tab === this.value
            },
            select () {
                this.$dispatch('select-tab', {
                    value: this.value,
                    label: this.label,
                })
            },
        }"
        x-init="active && select()"
        x-on:click="() => {
            if (href) window.location = href
            else select()
        }"
        x-bind:class="active ? 'active bg-slate-100' : 'cursor-pointer hover:bg-slate-100'"
        class="sidenav-item flex items-center gap-3 py-1.5 px-3 -mx-2 rounded">
        @isset($icon)
            {{ $icon }}
        @elseif ($icon = $attributes->get('icon'))
            <div x-bind:class="active ? 'text-theme' : 'text-gray-400'" class="shrink-0 w-4 flex">
                <x-icon :name="$icon" class="m-auto"/>
            </div>
        @endisset

        <div x-bind:class="active ? 'text-theme-dark font-semibold' : 'text-gray-600 font-medium'" class="grow">
            {{ tr($label) }}
        </div>

        @if ($badge)
            @foreach ((array) $badge as $key => $val)
                <div class="shrink-0">
                    <x-badge :label="$val" :color="is_string($key) ? $key : 'gray'"/>
                </div>
            @endforeach
        @endif

        @if ($count)
            <div class="shrink-0">
                <x-badge :label="$count"/>
            </div>
        @endif
    </div>
@endif