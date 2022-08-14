<x-dropdown>
    <x-slot:anchor>
        <div class="flex">
            <x-icon name="language" class="m-auto"/>
        </div>
    </x-slot:anchor>

    @foreach (config('atom.locales') as $locale)
        <x-dropdown.item 
            :href="route('__locale.set', [$locale])" 
            :label="metadata('locales', $locale)->name"
        />
    @endforeach
</x-dropdown>
