<x-dropdown>
    <x-slot:anchor>
        <div {{ $attributes->merge([
            'class' => 'flex items-center justify-center gap-2 px-3 text-center font-medium',
        ]) }}>
            @if ($label = $attributes->get('label')) {{ __($label) }}
            @elseif (isset($anchor)) {{ $anchor }}
            @endif
            <x-icon name="chevron-down" size="12px"/>
        </div>
    </x-slot:anchor>

    <div class="grid">
        {{ $slot }}
    </div>
</x-dropdown>
