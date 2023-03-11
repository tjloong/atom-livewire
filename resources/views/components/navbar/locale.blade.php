@props([
    'locales' => [
        ['label' => 'English', 'pattern' => 'en*'],
        ['label' => 'Bahasa Melayu', 'pattern' => 'ms*'],
        ['label' => '中文', 'pattern' => 'zh*'],
    ],
])

<x-dropdown {{ $attributes->merge([
    'class' => 'flex items-center justify-center'
]) }}>
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
            class="flex items-center justify-center"
        >
            <x-icon name="language" size="22"/>
        </div>
    </x-slot:anchor>

    @foreach (config('atom.locales') as $name)
        <x-dropdown.item 
            :href="route('__locale.set', [$name])" 
            :label="data_get(
                collect($locales)->first(fn($val) => str($name)->is(data_get($val, 'pattern'))),
                'label',
            )"
            :icon="$name === app()->currentLocale() ? 'check' : null"
        />
    @endforeach
</x-dropdown>
