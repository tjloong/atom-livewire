@php
$max = $attributes->get('max', 5);
$avatars = collect($attributes->get('avatars'))->map(function($value) {
    $name = get($value, 'name');
    $avatar = get($value, 'avatar');

    if (!is_string($avatar)) $avatar = $avatar?->url;

    return compact('name', 'avatar');
});
@endphp

@if ($avatars->count())
<div class="flex items-center flex-wrap">
    @foreach ($avatars->take($max) as $avatar)
        <div 
            x-data
            x-tooltip.raw="{{ get($avatar, 'name') }}"
            class="-ml-2 first:-ml-0 rounded-full bg-gray-400 text-gray-100 flex items-center justify-center font-semibold border border-gray-300"
            style="width: 28px; height: 28px; z-index: 1;">
            {{ str(get($avatar, 'name'))->substr(0, 1) }}
        </div>
    @endforeach

    @if ($avatars->count() > $max)
        <div 
            class="-ml-2 first:-ml-0 rounded-full bg-gray-400 text-gray-100 text-sm font-medium flex items-center justify-center border border-gray-300"
            style="width: 28px; height: 28px; z-index: 1;">
            {{ $avatars->count() - $max }}
        </div>
    @endif
</div>
@endif