<x-dropdown {{ $attributes }}>
    <x-slot:anchor>
        <div 
            x-data="{
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
            x-bind:class="classes"
            class="flex"
        >
            <x-icon name="language" size="22" class="m-auto"/>
        </div>
    </x-slot:anchor>

    @foreach (config('atom.locales') as $locale)
        <x-dropdown.item 
            :href="route('__locale.set', [$locale])" 
            :label="metadata('locales', $locale)->name"
            :icon="$locale === app()->currentLocale() ? 'check' : null"
        />
    @endforeach
</x-dropdown>
