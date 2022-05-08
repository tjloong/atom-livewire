<x-form.checkbox {{ $attributes->except('tnc', 'marketing', 'links') }}>
    <div class="grid gap-1">
        <div class="text-gray-500">
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @elseif ($type === 'tnc')
                {{ __('I have read and agreed to the app\'s terms & conditions and privacy policy.') }}
            @elseif ($type === 'marketing')
                {{ __('I agree to be part of the app\'s database for future newsletter, marketing and promotions opportunities.') }}
            @endif
        </div>

        @if ($links)
            <div class="flex items-center gap-2">
            @foreach ($links as $label => $href)
                <a href="{{ $href }}" target="_blank" class="text-sm">
                    {{ $label }}
                </a>
                @if ($label !== array_key_last($links)) | @endif
            @endforeach
            </div>
        @endif
    </div>
</x-form.checkbox>
