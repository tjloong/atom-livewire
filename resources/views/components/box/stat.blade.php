<div class="bg-white border rounded-xl overflow-hidden py-5 px-6 hover:ring-1 hover:ring-gray-200 hover:ring-offset-2">
    <div class="shrink-0">
        @isset($label) {{ $label }}
        @elseif ($label = $attributes->get('label'))
            <div class="font-medium text-gray-500">
                {{ __($label) }}
            </div>
        @endisset

        @isset($value) {{ $value }}
        @else
            @php $value = $attributes->get('value'); @endphp
            @if (!empty($value) || is_numeric($value))
                <div class="text-3xl font-bold">
                    {{ __($value) }}
                </div>
            @endif
        @endif
    </div>

    {{ $slot }}
</div>
