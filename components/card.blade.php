@php
$inset = $attributes->get('inset', false);
$subtle = $attributes->get('subtle', false);
$variant = $attributes->get('variant');
$heading = $attributes->get('heading');
$data = $attributes->get('data');
$indicator = $attributes->get('indicator');
$trend = $attributes->get('trend');
$type = $attributes->get('type');
$color = $attributes->get('color');
$max = $attributes->get('max');
$min = $attributes->get('min');

$classes = $attributes->classes()
    ->add('relative rounded-lg bg-white border shadow-sm')
    ->add($inset ? '' : 'p-6')
    ->add($subtle ? 'bg-zinc-100 border-transparent' : 'border-zinc-200')
    ->add(match ($variant) {
        'stats' => 'h-36 overflow-hidden',
        'chart' => 'h-[350px]',
        default => '',
    })
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'data-atom-card' => true,
        'data-atom-card-inset' => $inset ? true : null,
    ])
    ->except(['inset', 'subtle', 'variant', 'heading', 'data', 'indicator', 'trend', 'min', 'max'])
    ;
@endphp

<div {{ $attrs }}>
    @if ($variant === 'stats')
        <div class="absolute inset-0 p-6" style="z-index: 2">
            <atom:subheading>@t($heading)</atom:subheading>

            <div class="text-3xl font-bold">
                @e($data)
            </div>

            @if ($indicator)
                <div class="flex items-center gap-2 {{ $indicator > 0 ? 'text-green-500' : 'text-red-500' }}">
                    <atom:icon :name="$indicator > 0 ? 'arrow-up' : 'arrow-down'" size="12"></atom:icon>
                    <div class="font-medium">@e(abs($indicator).'%')</div>
                </div>
            @endif
        </div>

        @if ($trend)
            <div class="absolute left-0 right-0 bottom-0 h-1/2" style="z-index: 1">
                <div x-data x-chart="{
                    type: 'trend',
                    data: {{ js($trend) }},
                    color: {{ js($indicator > 0 ? 'green' : ($indicator < 0 ? 'red' : null)) }},
                }"></div>
            </div>
        @endif
    @elseif ($variant === 'chart')
        <div class="space-y-4 flex flex-col w-full h-full">
            <atom:subheading>@t($heading)</atom:subheading>

            <div class="grow">
                <div x-data x-chart="{
                    type: {{ js($type) }},
                    data: {{ js($data) }},
                    color: {{ js($color) }},
                    max: {{ js($max) }},
                    min: {{ js($min) }},
                }"></div>
            </div>
        </div>
    @else
        @isset($cover)
            <figure {{ $cover->attributes->class([
                'first:rounded-t-lg last:rounded-b-lg bg-zinc-100 overflow-hidden',
                '[&>*:not(video)]:transistion-transform [&>*:not(video)]:duration-200 [&>*:not(video):hover]:scale-105',
            ]) }}>
                {{ $cover }}
            </figure>
        @endisset

        {{ $slot }}
    @endif
</div>