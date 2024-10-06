@php
$max = $attributes->get('max', 5);

$avatars = collect($attributes->get('avatars'))->map(function($value) {
    $name = get($value, 'name');
    $avatar = get($value, 'avatar');

    if (!is_string($avatar)) $avatar = $avatar?->url;

    return compact('name', 'avatar');
});

$size = pick([
    'xs' => $attributes->has('xs'),
    'md' => $attributes->has('md'),
    'lg' => $attributes->has('lg'),
    'xl' => $attributes->has('xl'),
    'sm' => true,
]);

$dimension = [
    'xs' => '20',
    'sm' => '24',
    'md' => '28',
    'lg' => '32',
    'xl' => '38',
][$size];

$text = [
    'xs' => 'text-xs',
    'sm' => 'text-sm',
    'md' => 'text-base',
    'lg' => 'text-lg',
    'xl' => 'text-xl',
][$size];
@endphp

@if ($avatars->count())
<div class="flex items-center flex-wrap">
    @foreach ($avatars->take($max) as $avatar)
        <div 
            x-data
            x-tooltip="{{ js(get($avatar, 'name')) }}"
            class="-ml-2 first:-ml-0 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center font-semibold border border-gray-300 {{ $text }} hover:ring-1 hover:ring-offset-1 hover:ring-gray-300"
            style="width: {{ $dimension }}px; height: {{ $dimension }}px; z-index: 1;">
            {{ str(get($avatar, 'name'))->substr(0, 1) }}
        </div>
    @endforeach

    @if ($avatars->count() > $max)
        <div 
            class="-ml-2 first:-ml-0 rounded-full bg-gray-200 text-gray-400 text-sm font-medium flex items-center justify-center border border-gray-300 {{ $text }}"
            style="width: {{ $dimension }}px; height: {{ $dimension }}px; z-index: 1;">
            {{ $avatars->count() - $max }}
        </div>
    @endif
</div>
@endif