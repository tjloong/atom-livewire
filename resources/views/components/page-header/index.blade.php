@props(['size' => $attributes->get('size')])

<div class="w-full flex flex-wrap justify-between {{ $size === 'sm' ? 'mb-4' : 'mb-6' }}">
    <div class="grow flex gap-4 my-1">
        @if (request()->query('back'))
            <div class="shrink-0 py-2">
                <x-page-header.back :size="$size" :href="request()->query('back')"/>
            </div>
        @elseif ($back)
            <div class="shrink-0 py-2">
                <x-page-header.back :size="$size" :href="is_string($back) ? str($back)->toHtmlString() : null"/>
            </div>
        @endif

        @isset($title)
            <div {{ $title->attributes->merge(['class' => 'self-center']) }}>
                {{ $title }}
            </div>
        @elseif ($title = $attributes->get('title'))
            <div class="self-center grid">
                <div class="text-gray-800 font-bold truncate {{ 
                    $size === 'sm' 
                        ? 'text-lg font-bold' 
                        : 'text-2xl font-bold' 
                }}">
                    {{ str(__($title))->toHtmlString() }}
                </div>

                @isset($subtitle)
                    <div class="text-gray-600 font-light font-medium">{{ $subtitle }}</div>
                @elseif($subtitle = $attributes->get('subtitle'))
                    <div class="text-gray-600 font-light font-medium {{
                        $size === 'sm'
                            ? 'text-sm'
                            : ''
                    }}">
                        {{ str(__($subtitle))->toHtmlString() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="shrink-0 self-center my-1">
        {{ $slot }}
    </div>
</div>
