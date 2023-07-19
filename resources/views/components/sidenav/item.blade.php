@props([
    'href' => $attributes->get('href'),
    'icon' => $attributes->get('icon'),
    'label' => $attributes->get('label'),
    'badge' => $attributes->get('badge'),
    'count' => $attributes->get('count'),
    'disabled' => $attributes->get('disabled'),
    'value' => $attributes->get('value') ?? $attributes->get('name') ?? $attributes->get('slug'),
])

<div 
    x-cloak
    x-data="{
        href: @js($href),
        disabled: @js($disabled),
        get active () {
            return value === @js($value)
        },
        select (val) {
            if (this.disabled) return
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
    x-bind:class="{
        'active bg-slate-100': active && !disabled,
        'cursor-pointer hover:bg-slate-100': !active && !disabled,
        'opacity-50 pointer-event-none': disabled,
    }"
    class="sidenav-item flex items-center gap-3 py-2 px-3 -mx-2 rounded"
>
    <div 
        x-bind:class="active && !disabled ? 'text-theme' : 'text-gray-400'" 
        class="shrink-0 w-4 flex"
    >
        <x-icon :name="$icon" class="m-auto"/>
    </div>

    <div 
        x-bind:class="active && !disabled ? 'text-theme-dark font-semibold' : 'text-gray-600 font-medium'" 
        class="grow"
    >
        {{ __($label) }}
    </div>

    @if ($badge)
        @foreach ((array) $badge as $key => $val)
            <div x-show="!active && !disabled" class="shrink-0">
                <x-badge :label="$val" :color="is_string($key) ? $key : 'gray'"/>
            </div>
        @endforeach
    @endif

    @if ($count)
        <div x-show="!active && !disabled" class="shrink-0">
            <x-badge :label="$count"/>
        </div>
    @endif

    <div x-show="active && !disabled" class="w-2 h-4 bg-theme rounded"></div>
</div>