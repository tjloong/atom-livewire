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
        <x-dropdown.item>
            <a href="{{ route('__locale.set', [$name]) }}" class="py-2 px-4 flex items-center gap-3 text-gray-800 hover:bg-slate-100">
                <div class="grow">{{ data_get(collect($locales)->first(fn($val) => str($name)->is(data_get($val, 'pattern'))), 'label') }}</div>
                <div class="shrink-0 flex items-center gap-2">
                    @if ($name === app()->currentLocale()) <x-icon name="check" size="12" class="text-green-500"/> @endif
                    <div class="bg-slate-100 rounded-lg text-sm px-2 border border-slate-100 text-gray-500">{{ $name }}</div>
                </div>
            </a>
        </x-dropdown.item>
    @endforeach
</x-dropdown>
