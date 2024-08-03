@php
$icon = $attributes->get('icon');
$href = $attributes->get('href');
$target = $attributes->get('target', '_self');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$element = pick([
    'a' => !empty($href),
    'form' => !empty($attributes->submitAction()),
    'div' => true,
]);
$title = $title ?? $heading ?? $attributes->getAny('title', 'heading');
$except = ['cover', 'title', 'heading', 'href', 'target', 'rel', 'submit', 'form'];
@endphp

<{{ $element }}
    @if ($element === 'a')
    href="{{ $href }}"
    target="{{ $target }}"
    rel="{{ $rel }}"
    @elseif (is_string($attributes->submitAction()))
    wire:submit.prevent="{{ $attributes->submitAction() }}"
    @endif
    {{ $attributes->merge([
        'class' => 'box group/box relative bg-white rounded-lg border shadow-sm w-full',
    ])->except($except) }}>
    <div class="absolute top-4 right-4 hidden group-[.is-loading]/box:block">
        <x-spinner size="20" class="text-theme"/>
    </div>

    @if ($title instanceof \Illuminate\View\ComponentSlot)
        <x-heading no-margin :attributes="$title->attributes->merge(['class' => 'p-4 rounded-t-lg'])">
            {{ $title }}
        </x-heading>
    @elseif ($title)
        <x-heading no-margin :title="$title" :icon="$icon" class="p-4 rounded-t-lg"/>
    @endif

    @isset ($figure)
        <div class="first:rounded-t-lg last:rounded-b-lg overflow-hidden">
            <figure {{ $figure->attributes->class([
                'h-72 bg-gray-200 flex items-center justify-center',
                'group-hover/box:scale-105 transition-transform duration-200',
            ]) }}>
                @if ($figure->isNotEmpty())
                    {{ $figure }}
                @else
                    <x-icon name="image" class="text-gray-300 text-xl"/>
                @endif
            </figure>
        </div>
    @endisset

    @if ($slot->isNotEmpty())
        {{ $slot }}
    @endif

    @isset ($foot)
        <div {{ $foot->attributes->merge(['class' => 'p-4 border-t bg-slate-100 rounded-b-lg']) }}>
            {{ $foot }}
        </div>
    @endisset
</{{ $element }}>
