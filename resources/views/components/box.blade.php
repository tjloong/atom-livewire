@if ($attributes->has('row'))
    <div {{ $attributes->merge(['class' => 'grid p-4 md:gap-4 md:grid-cols-5 hover:bg-gray-100'])->except('row', 'label', 'value') }}>
        <div class="md:col-span-2 font-semibold text-gray-500">
            @if ($label = $label ?? $attributes->get('label'))
                {{ $label }}
            @endif
        </div>
        <div class="md:col-span-3 text-right">
            @if ($value = $value ?? $attributes->get('value'))
                {{ $value }}
            @endif
        </div>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'box bg-white rounded-md border shadow']) }}">
        <div class="p-1">
            @if ($header = $header ?? $attributes->get('header'))
                <div class="pt-3 pb-4 px-3 border-b font-bold text-lg">
                    {{ $header }}
                </div>
            @endif

            {{ $slot }}
        </div>

        @isset ($buttons)
            <div class="bg-gray-100 p-4 rounded-b-md">
                {{ $buttons }}
            </div>
        @endisset
    </div>
@endif
