@if ($href || ($route && Route::has($route)))
    <a href="{{ $href ?: route($route, $params) }}" {{ $attributes
        ->merge(['class' => 'py-3 px-5 flex items-center gap-3 text-gray-800 hover:bg-gray-100'])
        ->except('href', 'icon', 'icon-type', 'icon-color')
    }}>
        @if ($attributes->get('icon'))
            <x-icon 
                name="{{ $attributes->get('icon') }}" 
                type="{{ $attributes->get('icon-type') ?? 'regular' }}" 
                size="18px"
                class="text-gray-400"
            />
        @endif

        @if ($label = $attributes->get('label')) {{ __($label) }}
        @else {{ $slot }}
        @endif
    </a>
@else
    <div {{ $attributes }}>
        {{ $slot }}
    </div>
@endif
