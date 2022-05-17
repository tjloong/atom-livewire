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
    
    @elseif ($href = $attributes->get('href'))
        <a href="{{ $href }}">
            {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
        </a>

    @else
        {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}

    @endif
</td>