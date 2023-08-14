@props([
    'label' => $attributes->get('label', 'Restore'),
    'callback' => $attributes->get('callback', 'restore'),
    'params' => $attributes->get('params'),
])

<x-button icon="restore" :label="$label" 
    x-on:click="$wire.call('{{ $callback }}', {{ json_encode($params) }})"
    {{ $attributes->except(['icon', 'label', 'callback', 'params', 'x-on:click']) }}/>