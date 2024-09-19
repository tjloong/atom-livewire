@php
$file = $attributes->get('file');
$size = $attributes->size('sm');

$name = get($file, 'name');
$src = get($file, 'endpoint_sm') ?? get($file, 'endpoint');
$type = get($file, 'is_image') ? 'image' : get($file, 'type');
$icon = get($file, 'icon') ?? 'file';
@endphp

@if ($size === 'lg')
    <div class="w-full flex flex-col gap-1">
        <div class="w-full rounded-md overflow-hidden shadow relative pt-[100%]">
            <div class="absolute inset-0 bg-gray-100 flex items-center justify-center">
                @if ($type === 'image')
                    <img src="{{ $src }}" class="w-full h-full object-cover">
                @else
                    <div class="shrink-0 text-gray-400 flex items-center justify-center" style="font-size: 2em;">
                        <x-icon :name="$icon"/>
                    </div>
                @endif
            </div>
        </div>

        @if (!$attributes->get('no-label'))
            <div class="text-sm text-gray-400 font-medium truncate text-center">
                {!! $name !!}
            </div>
        @endif
    </div>
@else
    <div {{ $attributes->class(['flex items-center gap-3'])->except('file') }}>
        <div class="shrink-0 w-12 h-12 rounded-lg bg-gray-100 border shadow-sm flex items-center justify-center overflow-hidden">
            @if ($type === 'image')
                <img src="{{ $src }}" class="w-full h-full object-cover">
            @else
                <div class="shrink-0 text-gray-400 text-xl flex items-center justify-center">
                    <x-icon :name="$icon"/>
                </div>
            @endif
        </div>

        @if (!$attributes->get('no-label'))
            <div class="grow grid">
                <div class="font-medium truncate">
                    {!! $name !!}
                </div>

                <div class="text-sm text-gray-500">
                    {{ $type }}
                </div>
            </div>
        @endif
    </div>
@endif
