<div {{ $attributes->class([
    'flex flex-col gap-2 md:flex-row md:items-center hover:bg-slate-100',
    $attributes->get('class', 'py-2 px-4'),
])->except('label') }}>
    <div class="md:w-2/5 font-medium text-gray-400 text-sm flex items-center gap-2">
        @if ($icon = $attributes->get('icon'))
            <x-icon :name="$icon"/>
        @endif

        {{ __(str()->upper($attributes->get('label'))) }}
    </div>

    <div class="md:w-3/5 md:text-right">
        {{ $slot }}
    </div>
</div>
