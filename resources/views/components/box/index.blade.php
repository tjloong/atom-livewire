<div class="box bg-white border shadow {{ $attributes->get('class', 'rounded-md') }}">
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
                        <x-icon
                            :name="is_string($icon) ? $icon : data_get($icon, 'name')"
                            :size="is_string($icon) ? '16' : data_get($icon, 'size', '16')"
                            :class="is_string($icon) ? null : data_get($icon, 'class')"
                        />
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
        <div class="bg-gray-100 p-4 rounded-b-md">
            {{ $foot }}
        </div>
    @endisset
</div>
