@props([
    'label' => $attributes->get('label', 'atom::button.delete.label'),
    'callback' => $attributes->get('callback', 'delete'),
    'params' => $attributes->get('params'),
    'reload' => $attributes->get('reload', false),
    'inverted' => $attributes->get('inverted', true),
])

<x-button c="red" icon="trash-can"
    :label="$label"
    :inverted="$inverted" 
    x-on:click="$dispatch('confirm', {
        title: '{{ __($attributes->get('title', 'atom::button.delete.confirm.title')) }}',
        message: '{{ __($attributes->get('message', 'atom::button.delete.confirm.message')) }}',
        type: 'error',
        onConfirmed: () => {
            $wire.call('{{ $callback }}', {{ json_encode($params) }})
                .then(() => {{ json_encode($reload) }} && location.reload())
        },
    })"
    {{ $attributes->except([
        'c', 
        'icon', 
        'label', 
        'title', 
        'message', 
        'callback', 
        'params', 
        'reload',
        'x-on:click',
    ]) }}/>
