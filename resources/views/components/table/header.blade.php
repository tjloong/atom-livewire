<div class="flex flex-wrap items-center gap-3 p-4">
    @if ($label = $attributes->get('label'))
        <div class="grow font-semibold flex items-center gap-2 uppercase">
            @if ($icon = $attributes->get('icon'))
                <x-icon :name="$icon" class="text-gray-400"/>
            @endif
            {{ __($label) }}
        </div>

        <div class="shrink-0 flex flex-wrap items-center gap-2">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>
