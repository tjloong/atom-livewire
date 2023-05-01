<div {{
    $attributes->class([
        'box bg-white border shadow print:shadow-transparent',
        $attributes->get('class', 'rounded-xl'),
    ])->except('header')
}}>
    <div class="p-1 flex flex-col divide-y">
        @isset($header)
            <div {{ $header->attributes->class([
                'pt-3 pb-4 px-4',
                $header->attributes->get('class', 'font-bold md:text-lg'),
            ]) }}>
                {{ $header }}
            </div>
        @elseif ($header = $attributes->get('header'))
            <div class="pt-3 pb-4 px-3 flex flex-wrap items-center justify-between gap-2">
                <div class="shrink-0 flex items-center gap-2">
                    @if($icon = $attributes->get('icon') ?? $attributes->get('header-icon'))
                        @if (str($icon)->is('*:*')) <x-icon :name="head(explode(':', $icon))" :class="last(explode(':', $icon))"/>
                        @else <x-icon :name="$icon" class="text-gray-500"/>
                        @endif
                    @endif

                    <div class="font-bold md:text-lg">
                        {{ __($header) }}
                    </div>
                </div>

                @isset($buttons)
                    <div class="shrink-0 flex items-center gap-2">
                        {{ $buttons }}
                    </div>
                @endisset
            </div>
        @endif

        <div>
            {{ $slot }}
        </div>
    </div>

    @isset ($foot)
        <div {{ $foot->attributes->class([
            'bg-gray-100 rounded-b-md',
            $foot->attributes->get('class', 'p-4'),
        ]) }}">
            {{ $foot }}
        </div>
    @endisset
</div>
