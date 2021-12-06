<div {{ $attributes->class(['field', 'error' => $attributes->get('error')]) }}>
    @isset($label)
        <label class="block text-sm block text-xs font-medium leading-5 text-gray-400 uppercase mb-1.5">
            {{ $label }}

            @if ($attributes->get('required'))
                <x-icon name="health" size="8px" class="text-red-400"/>
            @endif
        </label>
    @endisset

    {{ $slot }}

    @if ($attributes->get('caption'))
        <div class="text-xs text-gray-700 mt-1">
            {{ $attributes->get('caption') }}
        </div>
    @endif

    @if ($attributes->get('error'))
        <div class="text-xs text-red-500 font-medium mt-1">
            {{ $attributes->get('error') }}
        </div>
    @endif
</div>
