@php
$date = $attributes->get('date');
$href = $attributes->get('href');
$align = $attributes->get('align', 'left');
$valign = $attributes->get('valign', 'top');
$human = $attributes->get('human');
$label = $attributes->get('label');
$limit = $attributes->get('limit');
$trans = $attributes->get('trans');
$caption = $attributes->get('caption') ?? $attributes->get('small');
$colspan = $attributes->get('colspan');
$sprintf = $attributes->get('sprintf') ?? $attributes->get('printf');
$tooltip = $attributes->get('tooltip');
$checkbox = $attributes->get('checkbox');
$currency = $attributes->get('currency');
$datetime = $attributes->get('datetime');
$timestamp = $attributes->get('timestamp');
$percentage = $attributes->get('percentage');

$tags = $attributes->get('tags') ?? $attributes->get('tag');
$tags = collect(is_string($tags) ? explode(',', $tags) : $tags)->map(function($val) {
    $isEnum = $val instanceof \UnitEnum || $val instanceof \BackedEnum;
    $isLabel = $val instanceof \App\Models\Label || $val instanceof \Jiannius\Atom\Models\Label;
    if ($isEnum || $isLabel) return $val->badge();
    else return ['color' => 'gray', 'label' => trim($val)];
})->filter();

$badges = $attributes->get('badges') ?? $attributes->get('badge') ?? $attributes->get('status');
$badges = is_string($badges) 
    ? collect(explode(',', $badges))->map(fn($val) => trim($val))->filter()
    : collect($badges)->filter();

$image = $attributes->has('image') ? $attributes->get('image') : false;
$image = $image instanceof \App\Models\File ? $image->url : $image;

$avatar = $attributes->has('avatar') ? $attributes->get('avatar') : false;
$avatar = $avatar instanceof \App\Models\File ? $avatar->url : $avatar;

$element = $href ? 'a' : 'div';
$except = [
    'active', 
    'align',
    'avatar', 
    'badges', 
    'checkbox', 
    'colspan',
    'date', 
    'datetime', 
    'from-now', 
    'image',
    'label',
    'limit',
    'printf',
    'small',
    'sprintf',
    'status', 
    'tags', 
    'tooltip',
    'trans',
];

if ($date && $human) $body = format($date, 'human')->value();
elseif ($date) $body = format($date)->value();
elseif ($datetime) $body = format($datetime)->value().' <br><span class="text-sm uppercase text-gray-500">'.format($datetime, 'time')->value().'</span>';
elseif ($timestamp) $body = format($timestamp, 'datetime')->value();
elseif (str($label)->length() > 0) {
    if ($currency && is_numeric($label)) $body = format($label, $currency)->value();
    elseif ($percentage && is_numeric($label)) $body = str($label)->finish('%')->toString();
    elseif (is_numeric($limit)) {
        $body = str($label)->limit($limit)->toString();
        if (str($label)->length() > $limit && !$tooltip) $tooltip = $label;
    }
    else $body = $label;
}
elseif ($trans) {
    $params = collect($trans);
    $key = $params->shift();
    $body = tr($key, ...$params);
}
elseif ($sprintf) {
    $params = collect($sprintf);
    $format = $params->shift();
    $body = sprintf($format, ...$params);
}
elseif (!$image && !$avatar && !$tags->count() && !$badges->count()) $body = '--';
else $body = null;
@endphp

@if ($checkbox)
    <td
        x-on:click.stop="toggleCheckbox(@js($checkbox))"
        x-on:toggle-checkbox="toggleCheckbox(@js($checkbox))"
        class="align-top py-3 px-2 w-10 cursor-pointer"
        data-table-checkbox
        wire:key="td-checkbox-{{ $checkbox }}">
        <div
            x-bind:class="checkboxes.includes(@js($checkbox)) ? 'border-theme border-2' : 'border-gray-300'"
            class="mx-4 w-6 h-6 p-0.5 rounded shadow border bg-white">
            <div x-show="checkboxes.includes(@js($checkbox))" class="w-full h-full bg-theme flex text-white p-px">
                <x-icon name="check" class="text-xs m-auto"/>
            </div>
        </div>
    </td>
@elseif ($slot->isNotEmpty())
    <td {{ $attributes->merge([
        'colspan' => $colspan,
        'class' => 'py-3 px-4 whitespace-nowrap',
    ])->only(['colspan', 'class']) }}>
        {{ $slot }}
    </td>
@else
    <td colspan="{{ $colspan }}" class="relative whitespace-nowrap align-{{ $valign }}">
        <{{$element}} 
            {{ $attributes->class([
                'py-3 px-4 inline-flex gap-3 w-full',
                pick([
                    'items-start' => $valign === 'top',
                    'items-center' => $valign === 'center',
                    'items-end' => $valign === 'bottom',
                ]),
                pick([
                    'justify-start' => $align === 'left',
                    'justify-center' => $align === 'center',
                    'justify-end' => $align === 'right',
                ]),
                $attributes->hasLike('wire:*', 'x-*') || $href ? 'cursor-pointer text-blue-600' : null,
            ])->except($except) }}>
            @if ($image !== false || $avatar !== false || $body)
                <div class="shrink-0 grow flex gap-3 {{ pick([
                    'items-start' => $valign === 'top',
                    'items-center' => $valign === 'center',
                    'items-end' => $valign === 'bottom',
                ]) }}">
                    @if ($image !== false)
                        <div class="shrink-0">
                            <figure class="w-10 h-10 rounded-md border bg-gray-200 flex items-center justify-center overflow-hidden">
                                @if (!$image) <x-icon name="ban" class="text-gray-400 text-lg"/>
                                @else <img src="{!! $image !!}" class="w-full h-full object-cover">
                                @endif
                            </figure>
                        </div>
                    @endif

                    @if ($avatar !== false)
                        <div class="shrink-0">
                            <figure class="w-10 h-10 rounded-full border bg-gray-200 flex items-center justify-center overflow-hidden">
                                @if ($body) <span class="text-gray-500 font-bold text-sm">{{ format($body)->abbr() }}</span>
                                @else <img src="{!! $avatar !!}" class="w-full h-full object-cover"/>
                                @endif
                            </figure>
                        </div>
                    @endif

                    @if ($body)
                        <div class="grow flex flex-col text-{{ $align }}">
                            <div
                                @if ($tooltip) x-tooltip="{!! js(t($tooltip)) !!}" @endif
                                class="{{ $href || $caption ? 'font-medium' : '' }}">
                                {!! $body !!}
                            </div>
                            
                            @if ($caption)
                                <div class="text-sm text-gray-500">{!! $caption !!}</div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            @if ($badges->count())
                <div class="grow inline-flex flex-wrap gap-1 items-center {{
                    pick([
                        'justify-start' => $align === 'left' && empty($body),
                        'justify-center' => $align === 'center' && empty($body),
                        'justify-end' => $align === 'right' || !empty($body),
                    ])
                }}">
                    @foreach ($badges as $badge)
                        <x-badge :badge="$badge"/>
                    @endforeach
                </div>
            @endif
            
            @if ($tags->count())
                <div class="grow inline-flex flex-wrap gap-1 items-center {{
                    pick([
                        'justify-start' => $align === 'left' && empty($body),
                        'justify-center' => $align === 'center' && empty($body),
                        'justify-end' => $align === 'right' || !empty($body),
                    ])
                }}">
                    @foreach ($tags->take(2) as $tag)
                        <x-badge
                            label="{!! str()->limit(get($tag, 'label'), 30) !!}"
                            :color="get($tag, 'color')"
                            :lower="false">
                        </x-badge>
                    @endforeach

                    @if ($tags->count() > 2)
                        <x-badge :label="'+'.($tags->count() - 2)"/>
                    @endif
                </div>
            @endif
        </{{ $element }}>
    </td>
@endif