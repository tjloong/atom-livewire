<a 
    x-data="{
        classes: {},
        init () {
            if (this.config.scrollHide) this.toggleScroll(false)
            if (empty(this.classes)) this.classes['text-gray-800 hover:text-theme'] = true
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
    x-bind:class="classes"
    {{ $attributes->class([
        'py-1.5 px-3 flex items-center justify-center gap-2 font-medium',
        $attributes->get('class'),
    ])->except(['icon', 'label']) }}
>
    @if ($icon = $attributes->get('icon'))
        <x-icon :name="$icon" class="opacity-70"/>
    @endif

    @if ($label = $attributes->get('label')) {{ __($label) }}
    @else {{ $slot }}
    @endif
</a>
