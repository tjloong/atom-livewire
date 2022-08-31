<div class="box bg-white border shadow {{ $attributes->get('class', 'rounded-md') }}">
    <div class="p-1">
        @if ($header = $header ?? $attributes->get('header'))
            <div class="pt-3 pb-4 px-3 border-b">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="shrink-0">
                        <div class="flex items-center gap-2">
                            @if($headerIcon = $attributes->get('header-icon'))
                                <x-icon
                                    :name="is_string($headerIcon) ? $headerIcon : data_get($headerIcon, 'name')"
                                    :size="is_string($headerIcon) ? '16px' : data_get($headerIcon, 'size', '16px')"
                                    :class="is_string($headerIcon) ? null : data_get($headerIcon, 'class')"
                                />
                            @endif

                            <div class="font-bold md:text-lg">
                                {{ is_string($header) ? __($header) : $header }}
                            </div>
                        </div>
                    </div>

                    @isset($headerButtons)
                        {{ $headerButtons }}
                    @endisset
                </div>
            </div>
        @endif

        {{ $slot }}
    </div>

    @isset ($foot)
        <div class="bg-gray-100 p-4 rounded-b-md">
            {{ $foot }}
        </div>
    @endisset
</div>
