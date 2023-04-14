<div {{ $attributes->merge([
    'class' => 'py-2 px-4 flex flex-col gap-2 md:flex-row md:items-center print:flex-row print:items-center hover:bg-slate-100',
])->only('class') }}>
    <div class="md:w-2/5 print:w-2/5">
        @isset($label) {{ $label }}
        @elseif ($label = $attributes->get('label'))
            <div class="font-medium text-gray-400 text-sm flex items-center gap-2">
                @if ($icon = $attributes->get('icon')) <x-icon :name="$icon"/> @endif
                {!! str(__($label))->upper() !!}
            </div>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="md:w-3/5">
            {{ $slot }}
        </div>
    @else
        <div class="md:w-3/5 md:text-right">
            @if ($badge = $attributes->get('badge'))
                @if (is_string($badge)) <x-badge :label="$badge"/>
                @elseif (is_array($badge))
                    <div class="inline-flex items-center gap-2">
                        @foreach ($badge as $key => $val)
                            <x-badge :label="$val" :color="$key"/>
                        @endforeach
                    </div>
                @endif
            @elseif ($tags = $attributes->get('tags') ?? $attributes->get('tag'))
                @if (is_string($tags)) <span class="text-sm bg-gray-100 border rounded px-2 flex items-center gap-2">{{ $tags }}</span>
                @elseif (is_array($tags))
                    <div class="inline-flex flex-wrap items-center justify-end gap-2">
                        @foreach ($tags as $tag)
                            <span class="shrink-0 text-sm bg-gray-100 border rounded px-2 flex items-center gap-2">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            @elseif ($value = $attributes->get('value'))
                @if ($href = $attributes->get('href'))
                    <x-link :label="$value" :href="$href" :target="$attributes->get('target', '_self')"/>
                @else
                    {!! $value !!}
                @endif
            @elseif ($href = $attributes->get('href'))
                <x-link :href="$href" :target="$attributes->get('target', '_self')"/>
            @endif

            @if ($small = $attributes->get('small'))
                <div class="text-sm text-gray-500 font-medium">{!! $small !!}</div>
            @endif
        </div>
    @endif
</div>
