@php
$tab = $attributes->get('tab');
$rel = $attributes->get('rel') ?? get($tab, 'rel') ?? 'noopener noreferrer nofollow';
$href = $attributes->get('href') ?? get($tab, 'href');
$icon = $attributes->get('icon') ?? get($tab, 'icon');
$label = $attributes->get('label') ?? get($tab, 'label');
$value = $attributes->get('value') ?? get($tab, 'value');
$count = $attributes->get('count') ?? get($tab, 'count');
$target = $attributes->get('target') ?? get($tab, 'target') ?? '_blank';
$element = $href ? 'a' : 'button';
$except = ['tab', 'icon', 'label', 'value', 'count', 'class'];
@endphp

<{{ $element }}
    @if($element === 'button') x-on:click="value = {{ Js::from($value) }}" @endif
    {{ $attributes->merge([
        'href' => $href,
        'type' => $element === 'button' ? 'button' : null,
        'rel' => $element === 'a' ? $rel : null,
        'target' => $element === 'a' ? $target : null,
        'class' => 'shrink-0 rounded-md hover:bg-gray-50',
    ])->except($except) }}>
    <div
        x-bind:class="value === {{ Js::from($value) }} ? 'bg-white shadow-sm font-medium whitespace-nowrap w-max' : 'truncate text-gray-400'"
        class="{{ $attributes->get('class', 'py-1.5 px-3 rounded-md transition-colors duration-300') }}">
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            {!! tr($label) !!}
        @endif
    </div>
</{{ $element }}>
