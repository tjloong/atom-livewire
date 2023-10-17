@props([
    'href' => $attributes->get('href'),
    'label' => $attributes->get('label'),
    'badge' => $attributes->get('badge'),
    'count' => $attributes->get('count'),
    'disabled' => $attributes->get('disabled'),
    'value' => $attributes->get('value') ?? $attributes->get('name') ?? $attributes->get('slug'),
])

@if ($disabled)
    <div class="flex items-center gap-3 py-1.5 px-3 -mx-2 opacity-50 pointer-event-none">
        @if ($icon)
            <div class="shrink-0 w-4 flex text-gray-400">
                <x-icon :name="$icon" class="m-auto"/>
            </div>
        @endif

        <div class="grow text-gray-600 font-medium">
            {{ __($label) }}
        </div>
    </div>
@else
    <div 
        x-cloak
        x-data="{
            href: @js($href),
            get active () {
                return value === @js($value)
            },
            select (val) {
                if (this.href) return window.location = this.href

                value = val
                show = false

                this.input()
            },
            input () {
                if (!this.active) return
                this.$dispatch('input', { value, label: @js($label) })
            },
        }"
        x-init="input"
        x-on:click="select(@js($value))"
        x-bind:class="active ? 'active bg-slate-100' : 'cursor-pointer hover:bg-slate-100'"
        class="sidenav-item flex items-center gap-3 py-1.5 px-3 -mx-2 rounded">
        @if ($badge || $count)
            <div x-show="active" class="w-1 h-4 bg-theme rounded"></div>
        @endif

        @isset($icon)
            {{ $icon }}
        @elseif ($icon = $attributes->get('icon'))
            <div x-bind:class="active ? 'text-theme' : 'text-gray-400'" class="shrink-0 w-4 flex">
                <x-icon :name="$icon" class="m-auto"/>
            </div>
        @endisset

        <div x-bind:class="active ? 'text-theme-dark font-semibold' : 'text-gray-600 font-medium'" class="grow">
            {{ __($label) }}
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

        @if (!$badge && !$count)
            <div x-show="active" class="w-1 h-4 bg-theme rounded"></div>
        @endif
    </div>
@endif