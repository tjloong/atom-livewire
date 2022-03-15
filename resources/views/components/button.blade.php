@if ($renderable)
    @if ($attributes->has('href') || $attributes->has('x-bind:href') || $attributes->get(':href'))
        <a x-data="button" {{ 
            $attributes->class([$styles, 'inline-flex items-center gap-2'])
        }}>
            <x-loader size="{{ $size === 'xs' ? '14px' : '18px' }}"/>

            @if ($attributes->get('icon'))
                <x-icon name="{{ $attributes->get('icon') }}" :size="$iconSize"/>
            @endif

            {{ $slot }}
        </a>

    @else
        <button x-data="button" {{ 
            $attributes->class([$styles, 'inline-flex items-center gap-2'])->merge(['type' => 'button'])
        }}>
            <x-loader size="{{ $size === 'xs' ? '14px' : '18px' }}"/>

            @if ($attributes->get('icon'))
                <x-icon name="{{ $attributes->get('icon') }}" :size="$iconSize"/>
            @endif

            {{ $slot }}
        </button>

    @endif
@endif
