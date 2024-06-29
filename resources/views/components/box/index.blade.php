@php
$icon = $attributes->get('icon');
$href = $attributes->get('href');
$target = $attributes->get('target', '_self');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$element = $href ? 'a' : 'div';
$heading = $heading ?? $title ?? $attributes->get('heading') ?? $attributes->get('title');
$except = ['cover', 'heading'];
@endphp

<{{ $element }} {{ $attributes->merge([
    'href' => $element === 'a' ? $href : null,
    'target' => $element === 'a' ? $target : null,
    'rel' => $element === 'a' ? $rel : null,
])->except(['cover', 'heading', 'class']) }} class="group/box relative bg-white rounded-xl border shadow-sm *(:first):rounded-t-lg w-full">
    @if ($heading instanceof \Illuminate\View\ComponentSlot)
        <x-heading class="py-3 px-4" :attributes="$heading->attributes">
            {{ $heading }}
        </x-heading>
    @elseif ($heading)
        <x-heading class="py-3 px-4" :title="$heading" :icon="$icon"/>
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
        <div {{ $attributes->only('class') }}>
            {{ $slot }}
        </div>
    @endif

    @isset ($foot)
        <div {{ $foot->attributes->merge(['class' => 'py-3 px-4 bg-slate-100 rounded-b-xl']) }}>
            {{ $foot }}
        </div>
    @endisset
</{{ $element }}>
