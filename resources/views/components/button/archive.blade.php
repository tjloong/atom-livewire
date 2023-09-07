@props([
    'label' => $attributes->get('label', 'Archive'),
    'callback' => $attributes->get('callback', 'archive'),
    'count' => $attributes->get('count'),
    'params' => $attributes->get('params'),
    'reload' => $attributes->get('reload', false),
    'inverted' => $attributes->get('inverted', true),
])


<x-button icon="box-archive" 
    :label="$label.($count > 1 ? ' ('.$count.')' : '')"
    :inverted="$inverted"
    x-on:click="$wire.call('{{ $callback }}', {{ json_encode($params) }})
        .then(() => {{ json_encode($reload) }} && location.reload())"
    {{ $attributes->except(['label', 'callback', 'params', 'reload', 'inverted', 'x-on:click', 'icon']) }}/>
