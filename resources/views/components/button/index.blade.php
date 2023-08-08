@props([
    'block' => $attributes->get('block', false),
    'href' => $attributes->get('href'),
    'target' => $attributes->get('target', '_self'),

    'getColor' => function($color, $inverted = false, $outlined = false) {
        if ($inverted) {
            return [
                'black' => 'bg-gray-200 text-gray-600 hover:text-white hover:bg-black focus:ring-black',
                'theme' => 'bg-theme-light text-theme hover:bg-theme hover:text-theme-inverted focus:ring-theme',
                'green' => 'bg-green-100 text-green-500 hover:bg-green-500 hover:text-white focus:ring-green-500',
                'red' => 'bg-red-100 text-red-500 hover:bg-red-500 hover:text-white focus:ring-red-500',
                'blue' => 'bg-blue-100 text-blue-500 hover:bg-blue-500 hover:text-white focus:ring-blue-500',
                'yellow' => 'bg-amber-100 text-amber-400 hover:bg-amber-400 hover:text-white focus:ring-amber-400',
                'gray' => 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-600 focus:ring-gray-200',
                'google' => 'bg-rose-100 text-rose-500 hover:bg-rose-500 hover:text-white focus:ring-rose-500',
                'facebook' => 'bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white focus:ring-blue-600',
                'default' => 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 focus:bg-gray-100 focus:ring-gray-200',
            ][$color];
        }
        elseif ($outlined) {
            return [
                'black' => 'bg-white text-black border-2 border-black hover:bg-black hover:text-white focus:ring-gray-500',
                'theme' => 'bg-white text-theme border-2 border-theme hover:bg-theme hover:text-theme-light focus:ring-theme-light',
                'green' => 'bg-white text-green-500 border-2 border-green-500 hover:bg-green-500 hover:text-white focus:ring-green-200',
                'red' => 'bg-white text-red-500 border-2 border-red-500 hover:bg-red-500 hover:text-white focus:ring-red-200',
                'blue' => 'bg-white text-blue-500 border-2 border-blue-500 hover:bg-blue-500 hover:text-white focus:ring-blue-200',
                'yellow' => 'bg-white text-amber-400 border-2 border-amber-400 hover:bg-amber-400 hover:text-white focus:ring-amber-200',
                'gray' => 'bg-white text-gray-400 border-2 border-gray-200 hover:bg-gray-200 hover:text-gray-500 focus:ring-gray-100',
                'google' => 'bg-white text-rose-500 border-2 border-rose-500 hover:bg-rose-600 hover:text-white focus:ring-rose-500',
                'facebook' => 'bg-white text-blue-600 border-2 border-blue-600 hover:bg-blue-700 hover:text-white focus:ring-blue-600',
                'default' => 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 focus:bg-gray-100 focus:ring-gray-200',
            ][$color];
        }

        return [
            'black' => 'bg-black text-white focus:ring-black',
            'theme' => 'bg-theme text-theme-inverted hover:bg-theme-dark focus:ring-theme',
            'green' => 'bg-green-500 text-white hover:bg-green-600 focus:ring-green-500',
            'red' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500',
            'blue' => 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500',
            'yellow' => 'bg-amber-400 text-white hover:bg-amber-600 focus:ring-amber-400',
            'gray' => 'bg-gray-200 text-gray-600 hover:bg-gray-300 focus:ring-gray-200',
            'google' => 'bg-rose-500 text-white hover:bg-rose-600 focus:ring-rose-500',
            'facebook' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-600',
            'default' => 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 focus:bg-gray-100 focus:ring-gray-200',
        ][$color];
    },

    'getSize' => function($size) {
        return [
            'xs' => 'text-xs px-3 py-1',
            'sm' => 'text-sm px-4 py-1.5',
            'md' => 'px-4 py-2',
            'lg' => 'text-lg px-4 py-2',
            'xl' => 'text-xl px-5 py-3',
            '2xl' => 'text-2xl px-5 py-3',
        ][$size];
    },
])

<button 
    x-data
    @if ($href) x-on:click.stop="window.open(@js($href), @js($target))" @endif
    {{ 
        $attributes
            ->merge([
                'type' => 'button',
            ])->class([
                'inline-flex items-center justify-center gap-1.5 font-medium tracking-wide transition-colors duration-200 rounded-md',
                'focus:ring-2 focus:ring-offset-2',
                $block ? 'w-full' : null,
                $getColor(
                    $attributes->get('color') ?? $attributes->get('c') ?? 'default',
                    $attributes->get('inverted', false),
                    $attributes->get('outlined', false),
                ),
                $getSize($attributes->get('size') ?? $attributes->get('s') ?? 'md'),
            ])->except(['size', 'color', 'inverted', 'outlined', 'icon', 'label', 'block', 'href', 'target', 'c', 's'])
    }}
>
@if ($slot->isNotEmpty())
    {{ $slot }}
@else
    @props([
        'icon' => $attributes->get('icon'),
        'label' => $attributes->get('label'),
    ])

    @if ($label)
        @if ($icon)
            <div class="shrink-0 flex items-center justify-center">
                <x-icon :name="$icon"/>
            </div>
        @endif

        {{ __($label) }}
    @elseif ($icon)
        <div>
            <x-icon :name="$icon.' sm'"/>
        </div>
    @endif
@endif
</button>