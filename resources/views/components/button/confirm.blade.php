@props([
    'type' => $attributes->get('type', 'info'),
    'buttons' => $attributes->get('buttons', []),
    'callback' => $attributes->get('callback'),
    'params' => $attributes->get('params'),
    'href' => $attributes->get('href'),
])

<x-button x-on:click="$dispatch('confirm', {
    title: '{{ __($attributes->get('title', 'Confirm')) }}',
    message: '{{ __($attributes->get('message', 'Are you sure?')) }}',
    type: '{{ $type }}',
    buttons: {{ json_encode($buttons) }},
    onConfirmed: () => {
        if ({{ json_encode($href ?? false) }}) window.location = '{{ $href }}';
        else if ({{ json_encode($callback ?? false) }}) $wire.call('{{ $callback }}', {{ json_encode($params) }});
    },
})"
{{ $attributes->except('title', 'message', 'buttons', 'callback', 'href', 'params') }}/>
