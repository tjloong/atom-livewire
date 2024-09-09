@php
$field = $attributes->field();
$label = $attributes->get('label');
$nolabel = $attributes->get('no-label');
$value = $attributes->get('value');
$date = $attributes->get('date');
$json = $attributes->get('json', false);
$inline = $attributes->get('block') ? false : $attributes->get('inline', true);
$href = $attributes->get('href');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$target = $attributes->get('target', '_self');
$address = $attributes->get('address');

$badges = $attributes->get('badges') ?? $attributes->get('badge') ?? $attributes->get('status');
$badges = collect(is_string($badges) ? explode(',', $badges) : $badges)->filter()->map(fn($val, $key) => 
    is_string($val) ? [
        'label' => trim($val),
        'color' => is_string($key) ? $key : 'gray',
    ] : [
        'label' => get($val, 'label'),
        'color' => get($val, 'color'),
    ]
)->values();

$tags = $attributes->get('tags') ?? $attributes->get('tag');
$tags = collect(is_string($tags) ? explode(',', $tags) : $tags)->filter()->map(function ($val) {
    $isEnum = $val instanceof \UnitEnum || $val instanceof \BackedEnum;
    $isLabel = $val instanceof \App\Models\Label || $val instanceof \Jiannius\Atom\Models\Label;
    if ($isEnum || $isLabel) return $val->badge();
    else return ['color' => 'gray', 'label' => trim($val)];
})->values();
@endphp

<div {{ $attributes->class(array_filter([
    'group/field',
    !$nolabel && ($field || $label) ? (
        $inline ? 'grid md:grid-cols-3 gap-1 items-start' : 'flex flex-col gap-1'
    ) : null,
]))->only('class') }}>
    @if(!$nolabel && ($field || $label))
        <x-label :field="$field" :attributes="$attributes->only(['for', 'label', 'required'])"/>
    @endif

    <div class="{{ $inline ? 'md:col-span-2' : null }}">
        @if($slot->isNotEmpty())
            {{ $slot }}
        @else
            <div class="leading-6">
            @if ($badges->count())
                <div class="inline-flex flex-wrap gap-1 items-center md:justify-end">
                    @foreach ($badges as $badge)
                        <x-badge :label="get($badge, 'label')" :color="get($badge, 'color')"/>
                    @endforeach
                </div>
            @elseif ($tags->count())
                <div class="inline-flex flex-wrap gap-1 items-center md:justify-end">
                    @foreach ($tags as $tag)
                        <x-badge :label="get($tag, 'label')" :color="get($tag, 'color')" :lower="false"/>
                    @endforeach
                </div>
            @elseif ($href)
                <div class="grid">
                    <x-anchor :label="$value ?? $href" :href="$href" :target="$target" :ref="$rel" class="truncate"/>
                </div>
            @elseif ($attributes->hasLike('wire:*', 'x-*'))
                <div class="grid">
                    <x-anchor :label="$value ?? $href" :attributes="$attributes->whereStartsWith('wire:', 'x-')"/>
                </div>
            @elseif ($address)
                <address class="not-italic">
                    @if (is_string($address)) {!! tr($address) !!}
                    @else
                        @if ($name = get($address, 'name')) {!! $name !!}<br> @endif
                        @if ($company = get($address, 'company')) {!! $company !!}<br> @endif

                        @if (is_string(get($address, 'address'))) {!! get($address, 'address') !!}
                        @else {!! format($address)->address() !!}
                        @endif
                    @endif
                </address>
            @elseif($json) 
                @json($json)
            @elseif($date)
                <x-carbon :date="$date" :attributes="$attributes->only(['format', 'utc', 'human'])"/>
            @else
                {!! $value ?? '--' !!}
            @endif
            </div>
        @endif
    </div>
</div>
