@props([
    'file' => $attributes->get('file'),
    'icon' => $attributes->get('icon'),
    'size' => $attributes->get('size') ?? (
        $attributes->get('class') && str($attributes->get('class'))->is('*w-full*')
            ? null
            : '100'
    ),
    'circle' => $attributes->get('circle', false),
    'color' => $attributes->get('color', 'bg-gray-200'),
    'placeholder' => $attributes->get('placeholder'),
    'downloadable' => $attributes->get('downloadable'),
    'colors' => [
        '#feca57',
        '#ee5253',
        '#5f27cd',
        '#2e86de',
        '#01a3a4',
        '#0abde3',
        '#222f3e',
        '#f368e0',
    ],
])

@php $file = is_numeric($file) ? model('file')->find($file) : $file @endphp
@php $url = $file ? $file->url : $attributes->get('url') @endphp

<figure 
    {{ $attributes
        ->class(['relative', 'cursor-pointer' => $downloadable])
        ->merge(['style' => $size ? 'width: '.str($size)->finish('px').'; height: '.str($size)->finish('px').';' : null])
        ->only(['class', 'style']) }}
    {{ $attributes->except(['file', 'url', 'icon', 'size', 'circle', 'color', 'class']) }}
    @if ($downloadable && $url)
        x-data
        x-on:click="window.open(@js($url), '_blank')"
    @endif
>
    <div 
        {{ $attributes->class([
            'shadow overflow-hidden w-full h-full',
            $color !=='random' ? $color : null,
            $circle ? 'rounded-full' : 'rounded-lg',
        ])->only('class') }}
        @if ($color === 'random') style="background-color: {{ collect($colors)->random() }};" @endif
    >
        @if ($file)
            @if ($file->is_video) <video class="w-full h-full object-cover"><source src="{{ $file->url }}"></video>
            @elseif ($file->is_image) <img src="{{ $file->url }}" class="w-full h-full object-cover">
            @elseif ($file->type === 'youtube') <img src="https://img.youtube.com/vi/{{ data_get($file->data, 'vid') }}/default.jpg" class="w-full h-full object-cover">
            @endif

            @if (!$file->is_image)
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <x-icon 
                        :name="$file->icon" 
                        :size="$size * 0.3"
                        class="{{
                            $file->type === 'youtube' ? 'text-red-500' : (
                                $file->is_video ? 'text-blue-500' : 'text-gray-800'
                            )
                        }}"
                    />
                    @if ($size > 40)
                        <div class="grid">
                            <span class="truncate">{{ $file->name }}</span>
                        </div>
                    @endif
                </div>
            @endif
        @elseif ($url)
            <img src="{{ $url }}" class="w-full h-full object-cover">

            @if ($icon)
                <div class="absolute inset-0 flex items-center justify-center">
                    <x-icon :name="$icon" :size="$size * 0.3"/>
                </div>
            @endif
        @elseif ($placeholder)
            <div 
                class="absolute inset-0 flex items-center justify-center text-white font-bold"
                style="font-size: {{ $size * 0.35 }}px;"
            >
                {{ strtoupper(substr($placeholder, 0, 2)) }}
            </div>
        @endif

        @isset($buttons)
            <div class="absolute top-0 right-0 left-0 bg-gradient-to-b from-gray-500 to-transparent pt-2 px-2 pb-4 {{ $circle ? 'rounded-t-full' : 'rounded-t-lg' }}">
                <div class="flex items-center gap-2">
                    {{ $buttons }}
                </div>
            </div>
        @endisset
    </div>

    @if ($attributes->has('wire:remove') || $attributes->has('x-on:remove'))
        <div class="absolute -top-2 -right-4 bg-white rounded-full shadow">
            <x-close x-on:click.stop="$dispatch('remove')" color="red"/>
        </div>
    @endif
</figure>
