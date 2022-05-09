<div x-data="{
    init () {
        const script = 'https://cdn.jsdelivr.net/npm/sharer.js@latest/sharer.min.js'
        ScriptLoader.load(script).then(() => Sharer.init())
    },
}" class="inline-block">
    <x-dropdown right="{{ $attributes->get('right') }}">
        <x-slot:trigger>
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                <x-button label="Share" icon="share-alt"/>
            @endif
        </x-slot:trigger>
    
        @foreach ($sites as $site)
            <x-dropdown.item
                :label="$titles[$site] ?? str()->headline($site)"
                :icon="$icons[$site]['name'] ?? $site"
                :icon-type="$icons[$site]['type'] ?? 'logo'"
                :icon-color="$icons[$site]['color'] ?? 'text-gray-400'"
                href="#"
                data-sharer="{{ $site }}"
                data-url="{{ $attributes->get('url') }}"
                data-title="{{ $attributes->get('title') }}"
                x-on:click.prevent=""
            />
        @endforeach    
    </x-dropdown>
</div>
