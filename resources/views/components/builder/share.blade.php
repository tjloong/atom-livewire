<div x-data="share" class="inline-block">
    <x-dropdown right="{{ $attributes->get('right') }}">
        <x-slot name="trigger">
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                <x-button icon="share-alt">Share</x-button>
            @endif
        </x-slot>
    
        @foreach ($sites as $site)
            <x-dropdown item 
                :icon="$icons[$site]['name'] ?? $site"
                :icon-type="$icons[$site]['type'] ?? 'logo'"
                :icon-color="$icons[$site]['color'] ?? 'text-gray-400'"
                href="#"
                data-sharer="{{ $site }}"
                data-url="{{ $attributes->get('url') }}"
                data-title="{{ $attributes->get('title') }}"
                x-on:click.prevent=""
            >
                {{ $titles[$site] ?? Str::headline($site) }}
            </x-dropdown>
        @endforeach    
    </x-dropdown>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('share', () => ({
                init () {
                    ScriptLoader.load('https://cdn.jsdelivr.net/npm/sharer.js@latest/sharer.min.js').then(() => {
                        Sharer.init()
                    })
                }
            }))
        })
    </script>
</div>
