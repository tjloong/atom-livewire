@php
    $icon = $attributes->get('icon');
    $href = $attributes->get('href');
    $tags = $attributes->get('tags') ?? $attributes->get('tag');
    $value = $attributes->get('value');
    $badge = $attributes->get('badge');
    $target = $attributes->get('target', '_self');
    $truncate = $attributes->get('truncate', false);
    $hide = $attributes->get('hide-empty', false);
    $except = ['label', 'icon', 'href', 'tags', 'value', 'badge', 'target', 'truncate'];
@endphp

@if (!$hide || ($hide && (
    !empty($value)
    || !empty($badge)
    || !empty($tags)
    || !empty($href)
)))
    <div {{ $attributes->merge([
        'class' => 'py-2 px-4 flex flex-col gap-2 md:flex-row md:items-center print:flex-row print:items-center hover:bg-slate-50',
    ])->only('class') }}>
        <div class="md:w-2/5 print:w-2/5">
            @isset($label) {{ $label }}
            @elseif ($label = $attributes->get('label'))
                <div class="font-medium text-gray-400 text-sm flex items-center gap-2">
                    @if ($icon) <x-icon :name="$icon"/> @endif
                    {!! str(tr($label))->upper() !!}
                </div>
            @endif
        </div>

        @if ($slot->isNotEmpty())
            <div class="md:w-3/5">
                {{ $slot }}
            </div>
        @else
            <div class="md:w-3/5 md:text-right">
                @if (is_string($badge))
                    <x-badge :label="$badge"/>
                @elseif (is_array($badge))
                    <div class="inline-flex items-center gap-2">
                        @foreach ($badge as $key => $val)
                            <x-badge :label="$val" :color="$key"/>
                        @endforeach
                    </div>
                @elseif (is_string($tags)) 
                    <span class="text-sm bg-gray-100 border rounded px-2 flex items-center gap-2">
                        <x-icon name="tag" class="text-xs text-gray-400"/> {{ $tags }}
                    </span>
                @elseif (is_array($tags))
                    <div class="inline-flex flex-wrap items-center justify-end gap-2">
                        @foreach ($tags as $tag)
                            <span class="shrink-0 text-sm bg-gray-100 border rounded px-2 flex items-center gap-2">
                                <x-icon name="tag" class="text-xs text-gray-400"/> {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                @elseif ($href || $attributes->hasLike('wire:*', 'x-*'))
                    <div class="grid">
                        <x-link :label="$value ?? $href"
                            :href="$href"
                            :target="$target"
                            class="{{ $truncate ? 'truncate' : '' }}"
                            {{ $attributes->except($except) }}/>
                    </div>
                @else
                    <div class="grid">
                        <div class="{{ $truncate ? 'truncate' : '' }}" {{ $attributes->except($except) }}>
                            {!! $value !!}
                        </div>
                    </div>
                @endif

                @if (isset($small))
                    <div class="text-sm text-gray-500 font-medium">{{ $small }}</div>
                @elseif ($small = $attributes->get('small'))
                    <div class="text-sm text-gray-500 font-medium">{!! $small !!}</div>
                @endif
            </div>
        @endif
    </div>
@endif
