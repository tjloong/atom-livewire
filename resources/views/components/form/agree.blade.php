<x-form.checkbox {{ $attributes->except('tnc', 'marketing', 'links') }}>
    <div class="grid gap-1">
        <div class="text-gray-500">
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @elseif ($type === 'tnc')
                {{ __('I have read and agreed to the website\'s Terms of Use and Privacy Policy.') }}
            @elseif ($type === 'marketing')
                {{ __('I agree to be part of the website\'s database for future marketing and promotional opportunities.') }}
            @endif
        </div>

        @if ($links)
            <div class="flex flex-col md:flex-row md:flex-wrap md:items-center md:gap-2">
            @foreach ($links as $label => $href)
                <a href="{{ $href }}" target="_blank" class="shrink-0 text-sm">
                    {{ $label }}
                </a>
                @if ($label !== array_key_last($links)) <span class="hidden md:block">|</span> @endif
            @endforeach
            </div>
        @endif
    </div>
</x-form.checkbox>
