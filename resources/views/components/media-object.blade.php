@php
    $align = $attributes->get('align');
    $valign = $attributes->get('valign', 'top');
    $label = $attributes->get('label');
    $caption = $attributes->get('caption');
    $content = $attributes->get('content');

    $avatar = $attributes->has('avatar') ? $attributes->get('avatar') : false;
    $avatar = $avatar->url ?? $avatar;

    $image = $attributes->has('image') ? $attributes->get('image') : false;
    $image = $image->url ?? $image;

    $size = pick([
        'xs' => $attributes->get('xs', false),
        'sm' => $attributes->get('sm', false),
        'md' => $attributes->get('md', false),
        'lg' => $attributes->get('lg', false),
        'xl' => $attributes->get('xl', false),
        '2xl' => $attributes->get('2xl', false),
        'base' => true,
    ]);
@endphp

<div {{ $attributes->class([
    'flex gap-3',
    pick([
        'items-start' => $valign === 'top',
        'items-center' => $valign === 'middle',
        'items-end' => $valign === 'bottom',
    ]),
    pick([
        'justify-start' => $align === 'left',
        'justify-center' => $align === 'center',
        'justify-end' => $align === 'right',
    ]),
]) }}>
    @if ($image !== false)
        <figure class="{{ collect([
            'shrink-0 rounded-md border bg-gray-100 flex items-center justify-center text-gray-400',
            pick([
                'w-5 h-5 text-xs' => $size === 'xs',
                'w-8 h-8 text-sm' => $size === 'sm',
                'w-12 h-12 text-base' => $size === 'base',
                'w-16 h-16 text-lg' => $size === 'md',
                'w-20 h-20 text-2xl' => $size === 'lg',
                'w-24 h-24 text-4xl' => $size === 'xl',
                'w-28 h-28 text-5xl' => $size === '2xl',
            ]),
            pick([
                'order-last' => $align === 'right',
                'order-first' => true,
            ]),
        ])->filter()->join(' ') }}">
            @if (is_string($image)) <img src="{{ $image }}" class="w-full h-full object-cover">
            @else <x-icon name="image"/>
            @endif
        </figure>
    @endif

    @if ($avatar !== false)
        <figure class="{{ collect([
            'shrink-0 rounded-full bg-gray-400 flex items-center justify-center text-gray-100 font-bold',
            pick([
                'w-5 h-5 text-xs' => $size === 'xs',
                'w-8 h-8 text-sm' => $size === 'sm',
                'w-12 h-12 text-base' => $size === 'base',
                'w-16 h-16 text-2xl' => $size === 'md',
                'w-24 h-24 text-4xl' => $size === 'lg',
                'w-28 h-28 text-5xl' => $size === 'xl',
                'w-32 h-32 text-6xl' => $size === '2xl',
            ]),
            pick([
                'order-last' => $align === 'right',
                'order-first' => true,
            ]),
        ])->filter()->join(' ') }}">
            @if (is_string($avatar)) <img src="{{ $avatar }}" class="w-full h-full object-cover">
            @else {{ format(tr($label))->abbr($size === 'xs' ? 1 : 2) }}
            @endif
        </figure>
    @endif

    @if ($slot->isNotEmpty())
        <div class="grow">{{ $slot }}</div>
    @else
        <div class="grow flex flex-col gap-2">
            <div>
                <div class="font-medium">
                    {!! tr($label) !!}
                </div>

                @if ($caption)
                    <div class="text-sm text-gray-500">
                        {!! tr($caption) !!}
                    </div>
                @endif
            </div>

            @if ($content)
                <div class="text-sm">
                    {!! $content !!}
                </div>
            @endif
        </div>
    @endif
</div>