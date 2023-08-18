<div {{
    $attributes->class([
        'box bg-white border shadow flex flex-col divide-y',
        $attributes->get('class', 'rounded-lg'),
    ])->except('header')
}}>
    @isset($header)
        <div class="py-2 px-4">
            {{ $header }}
        </div>
    @elseif ($header = $attributes->get('header'))
        <div class="py-3 px-4 flex flex-wrap items-center justify-between gap-2">
            <div class="shrink-0 flex items-center gap-2">
                @if($icon = $attributes->get('icon') ?? $attributes->get('header-icon'))
                    <x-icon :name="$icon" class="text-gray-500"/>
                @endif

                <div class="font-semibold">
                    {{ __(str()->upper($header)) }}
                </div>
            </div>

            @isset($buttons)
                <div class="shrink-0 flex items-center gap-2">
                    {{ $buttons }}
                </div>
            @endisset
        </div>
    @endif

    <div class="p-1">
        {{ $slot }}
    </div>

    @isset ($foot)
        <div class="py-3 px-4 bg-slate-100 rounded-b-lg">
            {{ $foot }}
        </div>
    @endisset
</div>
