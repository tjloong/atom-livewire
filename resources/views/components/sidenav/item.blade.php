<a
    x-data="{
        name: '{{ $attributes->get('name') ?? Illuminate\Support\Str::slug($slot->toHtml()) }}',
        get active () { return this.value === this.name },
    }"
    x-on:click.prevent="show = !show; (!active && $dispatch('input', name))"
    x-bind:class="{
        'font-bold text-theme bg-white drop-shadow border md:drop-shadow-none md:border-0': active && !show,
        'font-bold text-theme bg-white': active && show,
        'hidden font-medium text-gray-600 hover:bg-gray-100 hover:font-bold md:block': !active && !show,
        'font-medium text-gray-600 hover:bg-gray-100 hover:font-bold': !active && show,
    }"
    wire:loading.class="pointer-events-none"
    class="py-2 px-3 flex items-center space-x-2 rounded-md"
    {{ $attributes->except('name') }}
>
    @if ($attributes->has('icon'))
        <div
            x-bind:class="active ? 'text-theme' : 'text-gray-400'"
            class="flex-shrink-0 flex items-center justify-center"
        >
            <x-icon name="{{ $attributes->get('icon') }}" type="{{ $attributes->get('icon-type') ?? 'regular' }}" size="20px"/>
        </div>
    @endif

    <div class="flex-grow">
        {{ $slot }}
    </div>

    <div x-show="active && !show" class="flex-shrink-0 flex items-center justify-center md:hidden">
        <x-icon name="chevron-down"/>
    </div>
</a>