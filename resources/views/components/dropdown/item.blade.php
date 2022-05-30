@props([
    'class' => 'py-3 px-5 flex items-center gap-3 text-gray-800 hover:bg-gray-100',
    'except' => ['href', 'icon', 'icon-type', 'icon-color'],
    'label' => $attributes->get('label'),
])

@if ($attributes->has('href') || $attributes->wire('click')->value())
    <a {{ $attributes->merge(['class' => $class])->except(['icon', 'icon-type', 'icon-color']) }}>
        @if ($icon = $attributes->get('icon'))
            <x-icon 
                :name="$attributes->get('icon')"
                :type="$attributes->get('icon-type', 'regular')"
                size="18px"
                class="text-gray-400"
            />
        @endif

        {{ $label ? __($label) : $slot }}
    </a>
@else
    <div {{ $attributes }}>
        {{ $slot }}
    </div>
@endif
