<div x-data x-init="Sharer.init()" class="inline-block">
    <x-dropdown right="{{ $attributes->get('right') }}">
        <x-slot:anchor>
            <x-button :label="$attributes->get('label', 'Share')" icon="share-alt"/>
        </x-slot:anchor>

        @foreach ($sites as $site)
            <x-dropdown.item
                :label="$titles[$site] ?? str()->headline($site)"
                :icon="$icons[$site]['name'] ?? $site"
                {{-- :icon-type="$icons[$site]['type'] ?? 'logo'"
                :icon-color="$icons[$site]['color'] ?? 'text-gray-400'" --}}
                href="#"
                data-sharer="{{ $site }}"
                data-url="{{ $attributes->get('url') }}"
                data-title="{{ $attributes->get('title') }}"
                x-on:click.prevent=""
            />
        @endforeach

        <x-dropdown.item
            label="Copy site link"
            icon="link"
            href="#"
            x-on:click.prevent="$clipboard('{{ $attributes->get('url') }}').then(() => $dispatch('toast', {
                message: '{{ tr('app.label.url-copied') }}',
            }))"/>
    </x-dropdown>
</div>
