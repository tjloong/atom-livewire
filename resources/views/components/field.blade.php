@php
    $icon = $attributes->get('icon');
    $href = $attributes->get('href');
    $tags = $attributes->get('tags') ?? $attributes->get('tag');
    $badges = $attributes->get('badges') ?? $attributes->get('badge') ?? $attributes->get('status');
    $value = $attributes->get('value');
    $target = $attributes->get('target', '_self');
    $truncate = $attributes->get('truncate', true);
    $hide = $attributes->get('hide-empty', false);
    $orientation = $attributes->get('col') ? 'col' : 'row';
    $except = ['label', 'icon', 'href', 'tags', 'value', 'badges', 'status', 'target', 'col', 'row', 'hide-empty'];
@endphp

@if (!$hide || ($hide && (
    !empty($value)
    || !empty($badge)
    || !empty($tags)
    || !empty($href)
)))
    <div {{ $attributes->merge($orientation === 'col'
        ? ['class' => 'flex flex-col gap-1 hover:bg-slate-50']
        : ['class' => 'py-2 px-4 flex flex-col gap-1 md:flex-row md:flex-wrap md:items-center md:justify-between hover:bg-slate-50'],
    )->only('class') }}>
        <div class="shrink-0">
            @isset($label) {{ $label }}
            @else
                <label>
                    <div class="flex items-center gap-2">
                        @if ($icon) <x-icon :name="$icon"/> @endif
                        {!! tr($attributes->get('label')) !!}
                    </div>
                </label>
            @endisset
        </div>

        @if ($slot->isNotEmpty()) {{ $slot }}
        @else
            <div class="flex flex-col items-end">
                @if ($badges)
                    <div class="inline-flex flex-wrap gap-1 items-center md:justify-end">
                        @foreach (collect(is_string($badges) ? explode(',', $badges) : $badges)->map(fn($val) => trim($val))->filter() as $key => $badge)
                            <x-badge :color="is_string($key) ? $key : null" label="{!! $badge !!}"/>
                        @endforeach
                    </div>
                @elseif ($tags)
                    <div class="inline-flex flex-wrap gap-1 items-center md:justify-end">
                        @foreach (collect(is_string($tags) ? explode(',', $tags) : $tags)->map(fn($val) => trim($val))->filter() as $tag)
                            <x-badge icon="tag" :label="$tag"/>
                        @endforeach
                    </div>
                @elseif ($href || $attributes->hasLike('wire:*', 'x-*'))
                    <div class="grid">
                        <x-link :label="$value ?? $href" :href="$href" :target="$target" class="truncate"
                            {{ $attributes->except($except) }}/>
                    </div>
                @else
                    <div class="grid">
                        <div
                            x-data="{ truncate: @js($truncate) }" 
                            x-on:click="truncate = !truncate"
                            x-bind:class="truncate && 'truncate'">
                            @if (is_array($value)) {!! json_encode($value) !!}
                            @else {!! $value ?? '--' !!}
                            @endif
                        </div>
                    </div>
                @endif

                @isset($caption)
                    <div class="text-sm text-gray-500">{{ $caption }}</div>
                @elseif ($caption = $attributes->get('caption') ?? $attributes->get('small'))
                    <div class="text-sm text-gray-500">{!! tr($caption) !!}</div>
                @endisset
            </div>
        @endif
    </div>
@endif
