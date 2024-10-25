@php
$max = $attributes->get('max', 2);
$badges = collect($attributes->get('badges'));
@endphp

<div class="inline-flex items-center gap-2 flex-wrap">
    @if ($badges->count())
        @foreach ($badges->take($max) as $badge)
            @if (is_string($badge)) <atom:_badge>@t($badge)</atom:_badge>
            @elseif (is_enum($badge)) <atom:_badge :status="$badge"></atom:_badge>
            @else <atom:_badge :color="get($badge, 'color')">@t(get($badge, 'label'))</atom:_badge>
            @endif
        @endforeach

        @if ($badges->count() > $max)
            <atom:_badge>+@e($badges->count() - $max)</atom:_badge>
        @endif
    @else
        {{ $slot }}
    @endif
</div>