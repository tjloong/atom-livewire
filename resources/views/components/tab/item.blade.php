<div
    x-data="{ 
        name: @js($attributes->get('name')),
        href: @js($attributes->get('href')),
        target: @js($attributes->get('target')),
        get active () { 
            return this.href === @js(url()->current()) || this.value === this.name 
        },
        select () {
            this.$dispatch('select-tab', {
                uid: this.uid,
                name: this.name,
                href: this.href,
                target: this.target,
            })
        },
    }"
    x-bind:class="{
        'text-theme-dark font-bold border-b-2 border-theme-dark': active,
        'font-medium text-gray-400 border-transparent hover:text-gray-600 hover:border-gray-400': !active,
    }"
    x-on:click.prevent="select"
    class="shrink-0 p-1 border-b-2 cursor-pointer"
>
    <div class="flex items-center gap-2">
        @if ($slot->isNotEmpty()) {{ $slot }}
        @else
            @if ($icon = $attributes->get('icon')) <x-icon :name="$icon"/> @endif
            @if ($label = $attributes->get('label')) <div data-label class="grow">{!! __($label) !!}</div> @endif
            @if ($count = $attributes->get('count'))
                <div class="shrink-0">
                    <div class="px-2 py-0.5 rounded-full bg-gray-200 text-gray-800 text-xs font-semibold">
                        {{ $count }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
