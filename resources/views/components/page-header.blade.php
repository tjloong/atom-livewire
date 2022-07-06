<div class="w-full mb-6 flex flex-wrap justify-between">
    <div class="flex-grow flex my-1">
        @if ($back)
            <div class="flex-shrink-0">
                <x-back-button :href="request()->query('back') ?? (is_string($back) ? $back : null)"/>
            </div>
        @endif

        @isset($title)
            <div class="self-center">
                {{ $title }}
            </div>
        @elseif ($title = $attributes->get('title'))
            <div class="self-center grid gap-1">
                <div class="text-gray-800 font-bold truncate {{ $attributes->has('small') ? 'text-xl font-semibold' : 'text-2xl font-bold' }}">
                    {{ str(__($title))->toHtmlString() }}
                </div>

                @isset($subtitle)
                    <div class="text-gray-600 font-light">{{ $subtitle }}</div>
                @elseif($subtitle = $attributes->get('subtitle'))
                    <div class="text-gray-600 font-light">{{ str(__($subtitle))->toHtmlString() }}</div>
                @endif
            </div>
        @endif
    </div>

    <div class="flex-shrink-0 my-1">
        {{ $slot }}
    </div>
</div>
