<div {{ $attributes->class([
    'flex flex-col gap-2 md:flex-row hover:bg-slate-50',
    $attributes->get('class', 'p-3'),
])->except('label') }}>
    <div class="md:w-2/5 font-medium text-gray-400 text-sm">
        <div class="flex items-center gap-2">
            @if ($icon = $attributes->get('icon'))
                <x-icon :name="$icon"/>
            @endif
    
            {{ __(str()->upper($attributes->get('label'))) }}
        </div>
    </div>

    <div class="md:w-3/5 md:text-right">
        {{ $slot }}
    </div>
</div>
