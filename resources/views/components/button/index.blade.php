@php
    $label = $attributes->get('label');
    $icon = $attributes->get('icon');
    $iconsuffix = $attributes->get('icon-suffix');
    $position = $attributes->get('position', 'start');
    $href = $attributes->get('href');
    $rel = $attributes->get('rel', 'noopener noreferrer nofollow');
    $target = $attributes->get('target', '_self');
    $disabled = $attributes->get('disabled', false);
    $block = $attributes->get('block', false);
    $inverted = $attributes->get('inverted', false);
    $outlined = $attributes->get('outlined', false);
    $color = $attributes->get('color') ?? $attributes->get('c') ?? 'default';

    $size = $attributes->get('size') ?? $attributes->get('s') ?? pick([
        'xs' => $attributes->get('xs'),
        'sm' => $attributes->get('sm'),
        'lg' => $attributes->get('lg'),
        'xl' => $attributes->get('xl'),
        '2xl' => $attributes->get('2xl'),
        'md' => true,
    ]);

    $baseClass = collect([
        $block ? 'flex w-full' : 'inline-flex',
        $disabled ? 'pointer-events-none opacity-50' : null,
        'items-center justify-center gap-2 font-medium tracking-wide rounded-md',
        'transition-colors duration-200 focus:ring-1 focus:ring-offset-1',
    ])->filter()->join(' ');

    $sizeClass = $icon && !$label ? [
        'xs' => 'text-xs p-[0.55em]',
        'sm' => 'text-sm p-[0.65em]',
        'md' => 'text-base p-3',
        'lg' => 'text-lg p-[0.7em]',
        'xl' => 'text-xl p-[0.85em]',
        '2xl' => 'text-2xl p-[0.75em]',
    ][$size] : [
        'xs' => 'text-xs px-3 py-1',
        'sm' => 'text-sm px-4 py-1.5',
        'md' => 'text-base px-4 py-2',
        'lg' => 'text-lg px-4 py-2',
        'xl' => 'text-xl px-5 py-3',
        '2xl' => 'text-2xl px-5 py-3',
    ][$size];

    $colorClass = collect([
        $inverted ? [
            'black' => 'bg-gray-200 text-gray-600 hover:text-white hover:bg-black focus:ring-black',
            'theme' => 'bg-theme-light text-theme hover:bg-theme hover:text-theme-inverted focus:ring-theme',
            'green' => 'bg-green-100 text-green-500 border border-green-200 hover:bg-green-500 hover:border-green-500 hover:text-white focus:ring-green-500',
            'red' => 'bg-red-100 text-red-500 border border-red-200 hover:bg-red-500 hover:border-red-500 hover:text-white focus:ring-red-500',
            'blue' => 'bg-blue-100 text-blue-500 border border-blue-200 hover:bg-blue-500 hover:border-blue-500 hover:text-white focus:ring-blue-500',
            'yellow' => 'bg-amber-100 text-amber-400 hover:bg-amber-400 hover:text-white focus:ring-amber-400',
            'gray' => 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-600 focus:ring-gray-200',
            'google' => 'bg-rose-100 text-rose-500 hover:bg-rose-500 hover:text-white focus:ring-rose-500',
            'facebook' => 'bg-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white focus:ring-blue-600',
            'linkedin' => 'bg-sky-100 text-sky-600 hover:bg-sky-600 hover:text-white focus:ring-sky-600',
            'default' => 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 focus:bg-gray-100 focus:ring-gray-200',
        ][$color] : null,

        $outlined ? [
            'black' => 'bg-white text-black border-2 border-black hover:bg-black hover:text-white focus:ring-gray-500',
            'theme' => 'bg-white text-theme border-2 border-theme hover:bg-theme hover:text-theme-light focus:ring-theme-light',
            'green' => 'bg-white text-green-500 border-2 border-green-500 hover:bg-green-500 hover:text-white focus:ring-green-200',
            'red' => 'bg-white text-red-500 border-2 border-red-500 hover:bg-red-500 hover:text-white focus:ring-red-200',
            'blue' => 'bg-white text-blue-500 border-2 border-blue-500 hover:bg-blue-500 hover:text-white focus:ring-blue-200',
            'yellow' => 'bg-white text-amber-400 border-2 border-amber-400 hover:bg-amber-400 hover:text-white focus:ring-amber-200',
            'gray' => 'bg-white text-gray-400 border-2 border-gray-200 hover:bg-gray-200 hover:text-gray-500 focus:ring-gray-100',
            'google' => 'bg-white text-rose-500 border-2 border-rose-500 hover:bg-rose-600 hover:text-white focus:ring-rose-500',
            'facebook' => 'bg-white text-blue-600 border-2 border-blue-600 hover:bg-blue-700 hover:text-white focus:ring-blue-600',
            'linkedin' => 'bg-white text-sky-600 border-2 border-sky-600 hover:bg-sky-700 hover:text-white focus:ring-sky-600',
            'default' => 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 focus:bg-gray-100 focus:ring-gray-200',
        ][$color] : null,

        !$inverted && !$outlined ? [
            'black' => 'bg-black text-white focus:ring-black',
            'theme' => 'bg-theme text-theme-inverted hover:bg-theme-dark focus:ring-theme',
            'green' => 'bg-green-500 text-white border-green-500 hover:bg-green-600 focus:ring-green-500',
            'red' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500',
            'blue' => 'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500',
            'yellow' => 'bg-amber-400 text-white hover:bg-amber-600 focus:ring-amber-400',
            'gray' => 'bg-gray-200 text-gray-600 hover:bg-gray-300 focus:ring-gray-200',
            'google' => 'bg-rose-500 text-white hover:bg-rose-600 focus:ring-rose-500',
            'facebook' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-600',
            'linkedin' => 'bg-sky-600 text-white hover:bg-sky-700 focus:ring-sky-600',
            'default' => 'bg-white text-gray-800 border border-gray-300 hover:bg-gray-100 focus:bg-gray-100 focus:ring-gray-200',
        ][$color] : null,
    ])->filter()->join(' ');

    $except = [
        'size', 'color', 'inverted', 'outlined', 
        'icon', 'label', 'block', 'href', 'target', 'recaptcha', 
        'c', 's', 'xs', 'sm', 'md', 'lg', 'xl', '2xl',
    ];
@endphp

@if ($href)
    <a {{ $attributes
        ->merge([
            'href' => $href,
            'target' => $target,
            'rel' => $rel,
        ])
        ->class([$baseClass, $colorClass, $attributes->get('class', $sizeClass)])
        ->except($except)
    }}>
        @if ($slot->isNotEmpty()) {{ $slot }}
        @else
            @if ($icon) <div class="shrink-0 flex"><x-icon :name="$icon" class="m-auto"/></div> @endif

            @if (is_array($label)) {!! tr(...$label) !!}
            @else {!! tr($label) !!}
            @endif

            @if ($iconsuffix) <div class="shrink-0 flex"><x-icon :name="$iconsuffix" class="m-auto"/></div> @endif
        @endif
    </a>
@else
    <button {{ $attributes
        ->merge(['type' => 'button'])
        ->class([$baseClass, $colorClass, $attributes->get('class', $sizeClass)])
        ->except($except)
    }}>
        @if ($slot->isNotEmpty()) {{ $slot }}
        @else
            @if ($icon) <div class="shrink-0 flex"><x-icon :name="$icon" class="m-auto"/></div> @endif

            @if (is_array($label)) {!! tr(...$label) !!}
            @else {!! tr($label) !!}
            @endif

            @if ($iconsuffix) <div class="shrink-0 flex"><x-icon :name="$iconsuffix" class="m-auto"/></div> @endif
        @endif
    </button>
@endif