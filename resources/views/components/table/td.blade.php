<td {{ $attributes->class(['align-top py-3 px-4 whitespace-nowrap']) }}>
    @if ($status = $attributes->get('status'))
        <x-badge :label="$status"/>

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
            <a href="{{ $href }}" class="truncate">
                {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
            </a>
        </div>

    @else
        <div class="grid">
            <div class="truncate">
                {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
            </div>
        </div>

    @endif
</td>