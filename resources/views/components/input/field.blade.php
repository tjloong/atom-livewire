<div {{ $attributes->class([
    'field', 
    'error' => $attributes->get('error'),
]) }}>
    @isset($label)
        <label>
            {{ $label }}

            @if ($attributes->get('required'))
                <x-icon name="health" size="8px" class="text-red-400"/>
            @endif
        </label>
    @endisset

    {{ $slot }}

    @if ($attributes->get('caption'))
        <div class="text-sm text-gray-700 mt-1">
            {{ $attributes->get('caption') }}
        </div>
    @endif

    @if ($attributes->get('error'))
        <div class="text-sm text-red-500 font-medium mt-1">
            {{ $attributes->get('error') }}
        </div>
    @endif
</div>
