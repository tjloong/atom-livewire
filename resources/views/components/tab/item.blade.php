<a
    x-data="{ 
        name: @js($attributes->get('name')),
        get active () { return this.value === this.name },
        select () {
            if (this.active) return
            this.value = this.name
        },
    }"
    x-bind:class="{
        'text-theme-dark font-bold border-b-2 border-theme-dark': active,
        'font-medium text-gray-400 border-transparent hover:text-gray-600 hover:border-gray-400': !active,
    }"
    x-on:click.prevent="select"
    class="flex-shrink-0 p-1 border-b-2 mr-4"
>
    @if ($label = $attributes->get('label')) {{ __($label) }}
    @else {{ $slot }}
    @endif
</a>
