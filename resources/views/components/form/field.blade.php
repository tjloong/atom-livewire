<div {{ $attributes->class(['flex flex-col gap-1']) }}>
    @if ($label = $label ?? $attributes->get('label'))
        <label class="flex items-center gap-2 font-medium leading-5 text-gray-400 text-sm">
            {{ is_string($label) ? __(strtoupper($label)) : $label }}

            @if ($tag = $attributes->get('label-tag'))
                <span class="bg-blue-100 text-blue-500 font-medium text-xs px-2 py-0.5 rounded-md">
                    {{ __($tag) }}
                </span>
            @endif

            @if ($attributes->get('required'))
                <x-icon name="asterisk" size="10" class="text-red-400"/>
            @endif
        </label>
    @endif

    <div>
        {{ $slot }}
    </div>

    @if ($caption = $attributes->get('caption'))
        <div class="text-sm text-gray-700">
            {{ __($caption) }}
        </div>
    @endif

    @if ($error = $attributes->get('error'))
        <div class="text-sm text-red-500 font-medium">
            {{ __($error) }}
        </div>
    @endif
</div>
