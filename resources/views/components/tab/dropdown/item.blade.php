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
    x-on:click="select"
    x-bind:class="active && 'active bg-gray-100 font-medium'"
    class="py-2 px-4 flex items-center gap-2 cursor-pointer hover:bg-gray-100"
>
    @if ($slot->isNotEmpty()) {{ $slot }}
    @else
        @if ($icon = $attributes->get('icon')) <x-icon :name="$icon" class="text-gray-400"/> @endif
        @if ($label = $attributes->get('label')) <div data-label class="grow">{{ __($label) }}</div> @endif
        @if ($count = $attributes->get('count'))
            <div class="shrink-0">
                <div class="px-2 py-0.5 rounded-full bg-gray-200 text-gray-800 text-xs font-semibold">
                    {{ $count }}
                </div>
            </div>
        @endif
    @endif
</div>