@php
$file = $attributes->get('file');
$variant = $attributes->get('variant', 'inline');
$removeable = $attributes->has('wire:remove') || $attributes->has('x-on:remove');

$name = get($file, 'name');
$src = get($file, 'endpoint_sm') ?? get($file, 'endpoint');
$type = get($file, 'is_image') ? 'image' : get($file, 'type');
$icon = get($file, 'icon') ?? 'file';

$attrs = $attributes->except(['file', 'variant']);
@endphp

@if ($variant === 'card')
    <div {{ $attrs }}>
        <div class="group w-full rounded-md overflow-hidden shadow relative pt-[100%]">
            <div class="absolute inset-0 bg-gray-100 flex items-center justify-center">
                @if ($type === 'image') <img src="{{ $src }}" class="w-full h-full object-cover">
                @else <atom:icon :name="$icon" size="50%" class="text-muted-more"/>
                @endif
            </div>

            @if ($removeable)
                <div
                    x-on:click.stop="$dispatch('remove')"
                    class="absolute inset-0 bg-black/80 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white cursor-pointer transition-opacity duration-200">
                    <atom:icon close size="40%"/>
                </div>
            @endif
        </div>
    </div>
@elseif ($variant === 'inline')
    <div {{ $attrs->class(['flex gap-3']) }}>
        <div class="shrink-0 w-12 h-12 rounded-lg bg-zinc-100 border shadow-sm flex items-center justify-center overflow-hidden">
            @if ($type === 'image') <img src="{{ $src }}" class="w-full h-full object-cover">
            @else <atom:icon :name="$icon" class="text-muted"/>
            @endif
        </div>

        <div class="grow grid">
            <div class="font-medium truncate">@ee($name)</div>
            <div class="text-sm text-muted">@e($type)</div>
        </div>

        @if ($removeable)
            <div
                x-tooltip="{{ js(t('remove')) }}"
                x-on:click.stop="$dispatch('remove')"
                class="shrink-0 text-muted-more flex justify-center cursor-pointer">
                <atom:icon delete/>
            </div>
        @endif
    </div>
@endif
