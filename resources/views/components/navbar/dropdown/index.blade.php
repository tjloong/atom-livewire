<x-dropdown>
    <x-slot:anchor>
        <div 
            {{-- x-data="{
                classes: {},
                init () {
                    if (this.config.scrollHide) this.toggleScroll(false)
                },
                toggleScroll (bool) {
                    const revealClassName = this.config.scrollReveal?.item
                    const hideClassName = this.config.scrollHide?.item
                    if (revealClassName) this.classes[revealClassName] = bool
                    if (hideClassName) this.classes[hideClassName] = !bool
                },
            }"
            x-on:scroll-reveal.window="toggleScroll(true)"
            x-on:scroll-hide.window="toggleScroll(false)"
            x-bind:class="classes" --}}
            {{ $attributes->class([
                'flex items-center justify-center gap-2 px-3 text-center font-medium',
                $attributes->get('class'),
            ])->except(['icon', 'label']) }}
        >
            @if ($icon = $attributes->get('icon'))
                <x-icon :name="$icon" size="12"/>
            @endif

            @if ($label = $attributes->get('label')) {{ __($label) }}
            @elseif (isset($anchor)) {{ $anchor }}
            @endif

            <x-icon name="chevron-down" size="12"/>
        </div>
    </x-slot:anchor>

    <div class="grid">
        {{ $slot }}
    </div>
</x-dropdown>
