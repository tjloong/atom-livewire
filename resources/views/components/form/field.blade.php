<div {{ $attributes->class(['flex flex-col gap-2']) }}>
    @if ($label = $label ?? $attributes->get('label'))
        <label class="form-label">
            {{ is_string($label) ? __($label) : $label }}
            @if ($attributes->get('required'))
                <x-icon name="health" size="8px" class="text-red-400"/>
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
