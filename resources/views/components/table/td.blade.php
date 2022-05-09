<td {{ $attributes->class(['align-top py-3 px-4 whitespace-nowrap']) }}>
    @if ($status = $attributes->get('status'))
        <x-badge>{{ $status }}</x-badge>
    @elseif ($href = $attributes->get('href'))
        <a href="{{ $href }}">
            {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
        </a>
    @else
        {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
    @endif
</td>