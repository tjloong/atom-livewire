@props([
    'getImage' => function() use ($attributes) {
        $src = $attributes->get('image') ?? $attributes->get('avatar');
        $placeholder = $attributes->get('placeholder');

        return $src || $placeholder ? [
            'src' => $src,
            'placeholder' => $placeholder,
            'is_avatar' => $attributes->has('avatar'),
        ] : null;
    }
])

@php
    $date = $attributes->get('date');
    $datetime = $attributes->get('datetime');
    $timestamp = $attributes->get('timestamp');

    $tags = $attributes->get('tags') ?? $attributes->get('tag');
    $tags = collect(is_string($tags) ? explode(',', $tags) : $tags)->map(fn($val) => trim($val))->filter();

    $badges = is_bool($attributes->get('active'))
        ? ($attributes->get('active') ? ['green' => 'active'] : ['gray' => 'inactive'])
        : ($attributes->get('badges') ?? $attributes->get('badge') ?? $attributes->get('status'));
    $badges = collect(is_string($badges) ? explode(',', $badges) : $badges)->map(fn($val) => trim($val))->filter();
@endphp

@if ($value = $attributes->get('checkbox'))
    <td 
        wire:click.stop="selectCheckbox(@js($value))"
        data-checkbox-value="{{ $value }}"
        class="align-top py-3 px-2 w-10 cursor-pointer">
        @if (in_array($value, $this->checkboxes))
            <div class="mx-4 w-6 h-6 p-0.5 rounded shadow border border-theme border-2">
                <div class="w-full h-full bg-theme"></div>
            </div>
        @else
            <div class="mx-4 w-6 h-6 p-0 5 rounded shadow border bg-white border-gray-300"></div>
        @endif
    </td>
@else
    <td 
        class="py-3 px-4 whitespace-nowrap {{ $attributes->get('class', 'align-top') }} {{ $getImage() ? 'w-4' : '' }}"
        {{ $attributes->except(['checkbox', 'status', 'active', 'tags', 'badges', 'date', 'datetime', 'from-now', 'avatar', 'image', 'class']) }}>
        @if ($badges->count())
            <div class="inline-flex flex-wrap gap-1 items-center">
                @foreach ($badges as $key => $badge)
                    <x-badge :label="$badge" :color="is_string($key) ? $key : null"/>
                @endforeach
            </div>
        @elseif ($tags->count())
            <div class="inline-flex flex-wrap gap-1 items-center">
                @foreach ($tags->take(2) as $tag)
                    <x-badge label="{!! str()->limit($tag, 30) !!}"/>
                @endforeach

                @if ($tags->count() > 2)
                    <x-badge :label="'+'.($tags->count() - 2)"/>
                @endif
            </div>
        @elseif ($date)
            @if ($attributes->get('human')) {{ format($date, 'human') }}
            @else {{ format($date) }}
            @endif
        @elseif ($datetime)
            <div>{{ format($datetime) }}</div>
            <div class="text-sm text-gray-500">{{ format($datetime, 'time') }}</div>
        @elseif ($timestamp)
            {{ format($timestamp, 'datetime') }}
        @elseif ($image = $getImage())
            <x-image :src="data_get($image, 'src')"
                :avatar="data_get($image, 'is_avatar')"
                :placeholder="data_get($image, 'placeholder')"
                size="40x40"
                color="purple"/>
        @elseif ($attributes->get('dropdown'))
            <x-dropdown icon="ellipsis-vertical">
                {{ $slot }}
            </x-dropdown>
        @elseif (isset($label) || $slot->isNotEmpty())
            <div class="grid">
                @if ($href = $attributes->get('href'))
                    <a 
                        href="{!! $href !!}" 
                        class="{{ $tooltip ? '' : 'truncate' }}" 
                        target="{{ $attributes->get('target', '_self') }}"
                        @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
                    >
                        {{ $label ?: (($slot->isNotEmpty() ? $slot : null) ?? '--') }}
                    </a>
                @else
                    <div 
                        class="{{ collect([
                            $tooltip ? null : 'truncate',
                            $attributes->has('wire:click') || $attributes->has('x-on:click') ? 'cursor-pointer font-semibold text-blue-500' : null,
                        ])->filter()->join(' ') }}"
                        @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
                    >
                        {{ $label ?: (($slot->isNotEmpty() ? $slot : null) ?? '--') }}
                    </div>
                @endif

                @if ($small = $attributes->get('small'))
                    <div class="text-sm text-gray-500 truncate font-medium">
                        {!! $small !!}
                    </div>
                @endif
            </div>
        @endif
    </td>
@endif