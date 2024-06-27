@php
$field = $attributes->field();
$label = $attributes->get('label');
$value = $attributes->get('value');
$date = $attributes->get('date');
$json = $attributes->get('json', false);
$inline = $attributes->get('block') ? false : $attributes->get('inline', true);
$href = $attributes->get('href');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$target = $attributes->get('target', '_self');

$badges = $attributes->get('badges') ?? $attributes->get('badge') ?? $attributes->get('status');
$badges = collect(is_string($badges) ? explode(',', $badges) : $badges)->filter()->map(fn($val, $key) => [
    'label' => trim($val),
    'color' => is_string($key) ? $key : 'gray',
]);

$tags = $attributes->get('tags') ?? $attributes->get('tag');
$tags = collect(is_string($tags) ? explode(',', $tags) : $tags)->filter()->map(fn($val) => [
    'label' => trim($val),
    'color' => 'gray',
]);
@endphp

<div {{ $attributes->class([
    'group/field grid items-start',
    $inline ? 'md:grid-cols-3' : null,
])->only('class') }}>
    @if($field || $label)
        <x-label :field="$field" :attributes="$attributes->only(['for', 'label'])"/>
    @endif

    <div class="md:col-span-2 mt-1 {{ $inline ? 'md:mt-0' : '' }}">
        @if($slot->isNotEmpty())
            {{ $slot }}
        @else
            <div class="leading-6">
            @if ($badges->count())
                <div class="inline-flex flex-wrap gap-1 items-center md:justify-end">
                    @foreach ($badges as $badge)
                        <x-badge :badge="$badge"/>
                    @endforeach
                </div>
            @elseif ($tags->count())
                <div class="inline-flex flex-wrap gap-1 items-center md:justify-end">
                    @foreach ($tags as $tag)
                        <x-badge :badge="$tag"/>
                    @endforeach
                </div>
            @elseif ($href || $attributes->hasLike('wire:*', 'x-*'))
                <div class="grid">
                    <x-anchor :label="$value ?? $href" :href="$href" :target="$target" :ref="$rel" class="truncate"/>
                </div>
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
