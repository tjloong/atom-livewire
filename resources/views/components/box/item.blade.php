<div {{ $attributes
    ->merge(['class' => 'grid p-4 md:gap-4 md:grid-cols-5 hover:bg-gray-100'])
    ->except('row', 'label', 'value') 
}}>
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
