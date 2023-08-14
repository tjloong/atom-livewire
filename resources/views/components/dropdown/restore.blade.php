@props([
    'label' => $attributes->get('label', 'Restore'),
    'callback' => $attributes->get('callback', 'restore'),
    'params' => $attributes->get('params'),
])

<x-dropdown.item :label="$label" icon="restore" 
    x-on:click="$wire.call('{{ $callback }}', {{ json_encode($params) }})"/>