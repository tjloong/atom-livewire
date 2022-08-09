<td {{ $attributes->class(['align-top py-3 px-4 whitespace-nowrap']) }}>
    @if ($attributes->has('status'))
        @if ($status = $attributes->get('status'))
            <x-badge :label="$status"/>
        @endif

    @elseif ($attributes->has('active'))
        <x-badge :label="$attributes->get('active') ? 'active' : 'inactive'"/>

    @elseif ($tags = $attributes->get('tags'))
        <div class="flex items-center gap-2">
            @foreach (collect($tags)->take(2) as $tag)
                <div class="text-sm font-medium bg-slate-100 rounded-md py-1 px-2 border">
                    {{ str($tag)->limit(12) }}
                </div>
            @endforeach

            @if (count($tags) > 2)
                <div class="text-sm font-medium bg-slate-100 rounded-md py-1 px-2 border">
                    +{{ count($tags) -  2 }}
                </div>
            @endif
        </div>

    @elseif ($date = $attributes->get('date'))
        {{ format_date($date) }}
    
    @elseif ($datetime = $attributes->get('datetime'))
        <div>{{ format_date($datetime) }}</div>
        <div class="text-sm text-gray-500">{{ format_date($datetime, 'time') }}</div>
    
    @elseif ($href = $attributes->get('href'))
        <div class="grid">
            <a 
                href="{{ $href }}" 
                class="{{ $tooltip ? '' : 'truncate' }}" 
                @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
            >
                {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
            </a>

            @if ($small = $attributes->get('small'))
                <div class="text-sm text-gray-500 truncate font-medium">
                    {{ $small }}
                </div>
            @endif
        </div>

    @else
        <div class="grid">
            <div 
                class="{{ $tooltip ? '' : 'truncate' }}" 
                @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
            >
                {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
            </div>

            @if ($small = $attributes->get('small'))
                <div class="text-sm text-gray-500 truncate font-medium">
                    {{ $small }}
                </div>
            @endif
        </div>
        
    @endif
</td>