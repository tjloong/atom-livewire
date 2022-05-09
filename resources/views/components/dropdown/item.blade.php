@if ($href || ($route && Route::has($route)))
    <a href="{{ $href ?: route($route, $params) }}" {{ $attributes
        ->merge(['class' => 'py-2 px-4 flex items-center gap-2 text-gray-700 hover:bg-gray-100'])
        ->except('href', 'icon', 'icon-type', 'icon-color')
    }}>
        @if ($attributes->get('icon'))
            <x-icon 
                name="{{ $attributes->get('icon') }}" 
                type="{{ $attributes->get('icon-type') ?? 'regular' }}" 
                size="18px"
                class="{{ $attributes->get('icon-color') ?? '' }}"
            />
        @endif

        @if ($label = $attributes->get('label')) {{ __($label) }}
        @else {{ $slot }}
        @endif
    </a>
@else
    <div {{ $attributes
        ->merge(['class' => 'py-2 px-4 flex items-center gap-2 text-gray-700 hover:bg-gray-100'])
        ->except('icon', 'icon-type', 'icon-color') 
    }}>
        @if ($attributes->get('icon'))
            <x-icon 
                name="{{ $attributes->get('icon') }}" 
                type="{{ $attributes->get('icon-type') ?? 'regular' }}" 
                size="18px"
                class="{{ $attributes->get('icon-color') ?? '' }}"
            />
        @endif

        @if ($label = $attributes->get('label')) {{ __($label) }}
        @else {{ $slot }}
        @endif
    </div>
@endif
