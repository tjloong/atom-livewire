<td {{ $attributes->class(['align-top py-3 px-4 whitespace-nowrap']) }}>
    @if ($attributes->has('status'))
        @if ($status = $attributes->get('status'))
            <x-badge :label="$status"/>
        @endif

    @elseif ($attributes->has('active'))
        <x-badge :label="$attributes->get('active') ? 'active' : 'inactive'"/>

    @elseif ($tags = $attributes->get('tags'))
        @if (count($tags))
            <div class="flex items-center gap-2">
                @foreach (collect($tags)->take(2) as $tag)
                    <div 
                        @if (strlen($tag) > 20) x-tooltip="{{ $tag }}" @endif
                        class="text-xs font-medium bg-slate-100 rounded-md py-0.5 px-2 border"
                    >
                        {{ str($tag)->limit(20) }}
                    </div>
                @endforeach

                @if (count($tags) > 2)
                    <div class="text-sm font-medium bg-slate-100 rounded-md py-1 px-2 border">
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
    
    @elseif ($attributes->has('avatar'))
        <div class="flex items-center gap-3">
            <div class="shrink-0 flex items-center justify-center">
                <x-avatar 
                    :url="$attributes->get('avatar')" 
                    :placeholder="$label ?? $slot->toString()" 
                    size="36"
                />
            </div>

            <div class="grid">
                @if ($href = $attributes->get('href'))
                    <a 
                        href="{{ $href }}" 
                        class="{{ $tooltip ? '' : 'truncate' }}" 
                        target="{{ $attributes->get('target', '_self') }}"
                        @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
                    >
                        {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
                    </a>
                @else
                    <div 
                        class="{{ $tooltip ? '' : 'truncate' }}" 
                        @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
                    >
                        {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
                    </div>
                @endif
    
                @if ($small = $attributes->get('small'))
                    <div class="text-sm text-gray-500 truncate font-medium">
                        {{ $small }}
                    </div>
                @endif
            </div>
        </div>

    @else
        <div class="grid">
            @if ($href = $attributes->get('href'))
                <a 
                    href="{{ $href }}" 
                    class="{{ $tooltip ? '' : 'truncate' }}" 
                    target="{{ $attributes->get('target', '_self') }}"
                    @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
                >
                    {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
                </a>
            @else
                <div 
                    class="{{ $tooltip ? '' : 'truncate' }}" 
                    @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
                >
                    {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
                </div>
            @endif

            @if ($small = $attributes->get('small'))
                <div class="text-sm text-gray-500 truncate font-medium">
                    {{ $small }}
                </div>
            @endif
        </div>

    @endif
</td>