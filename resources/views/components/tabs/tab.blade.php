<a
    x-data="{ 
        name: '{{ $attributes->get('name') ?? Illuminate\Support\Str::slug($slot->toHtml()) }}',
        get active () { return this.value === this.name },
    }"
    x-bind:class="{
        'text-theme-dark font-bold border-b-2 border-theme-dark': active,
        'font-medium text-gray-400 border-transparent hover:text-gray-600 hover:border-gray-400': !active,
    }"
    x-on:click.prevent="!active && $dispatch('input', name)"
    wire:loading.class="pointer-events-none"
    class="flex-shrink-0 p-1 border-b-2 mr-4"
>
    {{ $slot }}
</a>
