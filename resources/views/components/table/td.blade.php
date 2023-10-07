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

@if ($value = $attributes->get('checkbox'))
    <td 
        wire:click="selectCheckbox(@js($value))"
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
        {{ $attributes->except(['checkbox', 'status', 'active', 'tags', 'date', 'datetime', 'from-now', 'avatar', 'image', 'class']) }}>
        @if ($status = $attributes->get('status'))
            @if (is_string($status))
                <x-badge :label="$status"/>
            @elseif (is_array($status))
                @foreach (array_filter($status) as $key => $val)
                    <x-badge :label="$val" :color="is_string($key) ? $key : null"/>
                @endforeach
            @endif
        @elseif (is_bool($attributes->get('active')))
            <x-badge :label="$attributes->get('active') ? 'active' : 'inactive'"/>
        @elseif ($tags = $attributes->get('tags'))
            @if (count(array_filter($tags)))
                <div class="inline-flex items-center gap-2">
                    @foreach (collect($tags)->filter()->take(2) as $tag)
                        <div 
                            @if (strlen($tag) > 20) x-tooltip="{{ $tag }}" @endif
                            class="text-xs font-medium bg-slate-100 rounded-md py-0.5 px-2 border">
                            {{ str($tag)->limit(20) }}
                        </div>
                    @endforeach

                    @if (count($tags) > 2)
                        <div class="text-xs font-medium bg-slate-100 rounded-md py-1 px-2 border">
                            +{{ count($tags) -  2 }}
                        </div>
                    @endif
                </div>
            @else
                --
            @endif
        @elseif ($date = $attributes->get('date'))
            {{ format_date($date) }}
        @elseif ($datetime = $attributes->get('datetime'))
            <div>{{ format_date($datetime) }}</div>
            <div class="text-sm text-gray-500">{{ format_date($datetime, 'time') }}</div>
        @elseif ($fromNow = $attributes->get('from-now'))
            {{ format_date($fromNow, 'human') }}
        @elseif ($image = $getImage())
            <x-image :src="data_get($image, 'src')"
                :avatar="data_get($image, 'is_avatar')"
                :placeholder="data_get($image, 'placeholder')"
                color="random"/>
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