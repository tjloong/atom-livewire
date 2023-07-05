@props([
    'id' => $attributes->get('id', 'page-overlay'),
])

<div 
    x-cloak
    x-data="{
        show: false,
        get isAdminPanel () {
            return !empty(document.querySelector('#admin-panel'))
        },
        open () { this.show = true },
        close () { this.show = false},
    }"
    x-show="show"
    x-transition.opacity
    x-on:{{ $id }}-open.window="open"
    x-on:{{ $id }}-close.window="close"
    x-on:open="open"
    x-on:close="close"
    class="fixed z-10 top-0 bottom-0 right-0 left-0 lg:left-56 bg-white p-6"
    id="{{ $id }}"
>
    <div {{ $attributes->class(['flex flex-col gap-4']) }}>
        <div class="flex justify-end">
            <x-close x-on:click="$dispatch('close')" class="text-2xl"/>
        </div>

        <div>{{ $slot }}</div>
    </div>
</div>