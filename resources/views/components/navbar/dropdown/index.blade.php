<x-dropdown>
    <x-slot:anchor>
        <div {{ $attributes->merge([
            'class' => 'flex items-center justify-center gap-2 px-3 text-center font-medium',
        ])->except(['icon', 'label']) }}>
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
