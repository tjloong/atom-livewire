<x-dropdown>
    <x-slot:anchor>
        <div class="flex">
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
