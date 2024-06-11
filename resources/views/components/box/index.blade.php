@php
$icon = $attributes->get('icon');
$href = $attributes->get('href');
$target = $attributes->get('target', '_self');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$element = $href ? 'a' : 'div';
$heading = $heading ?? $attributes->get('heading');
$except = ['cover', 'heading'];
@endphp

<{{ $element }} {{ $attributes
    ->merge([
        'href' => $element === 'a' ? $href : null,
        'target' => $element === 'a' ? $target : null,
        'rel' => $element === 'a' ? $rel : null,
    ])
    ->class([
        'group relative flex flex-col divide-y bg-white rounded-lg border shadow',
        '*(:first):rounded-t-lg',
    ])
    ->except($except)
}}>
    @if ($heading instanceof \Illuminate\View\ComponentSlot)
        <x-heading :attributes="$heading->attributes">
            {{ $heading }}
        </x-heading>
    @elseif ($heading)
        <x-heading class="py-2 px-4" :title="$heading" :icon="$icon" sm/>
    @endif

    @isset ($figure)
        <div class="first:rounded-t-lg last:rounded-b-lg overflow-hidden">
            <figure {{ $figure->attributes->class([
                'h-72 bg-gray-200 flex items-center justify-center',
                'group-hover:scale-105 transition-transform duration-200',
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
        <div {{ $attributes->merge(['class' => 'p-px'])->only('class') }}>
            {{ $slot }}
        </div>
    @endif

    @isset ($foot)
        <div {{ $foot->attributes->merge(['class' => 'py-3 px-4 bg-slate-100 rounded-b-lg']) }}>
            {{ $foot }}
        </div>
    @endisset
</{{ $element }}>
