@props([
    'title' => __($attributes->get('title', 'Confirm')),
    'message' => __($attributes->get('message', 'Are you sure?')),
    'type' => $attributes->get('type', 'info'),
    'buttons' => $attributes->get('buttons', []),
    'callback' => $attributes->get('callback'),
    'params' => $attributes->get('params'),
    'href' => $attributes->get('href'),
])

<x-button
    {{ $attributes->except(['title', 'message', 'buttons', 'callback', 'href', 'params']) }}
    x-on:click="$dispatch('confirm', {
        title: '{{ $title }}',
        message: '{{ $message }}',
        type: '{{ $type }}',
        buttons: {{ json_encode($buttons) }},
        onConfirmed: () => {
            @if ($href) window.location = '{{ $href }}';
            @elseif ($callback) $wire.call('{{ $callback }}', {{ json_encode($params) }});
        },
    })"
/>
