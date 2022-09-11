<div 
    class="grid gap-2 md:grid-cols-5 {{ $attributes->get('class', 'p-4 hover:bg-slate-100') }}"
    {{ $attributes->except(['class', 'label']) }}
>
    <div class="md:col-span-2">
        <div class="font-medium text-gray-400">
            @if ($label = $label ?? $attributes->get('label'))
                {{ is_string($label) ? __(strtoupper($label)) : $label }}
            @endif
        </div>
    </div>

    <div class="md:col-span-3">
        <div class="md:flex md:justify-end">
            {{ $slot }}
        </div>
    </div>
</div>
