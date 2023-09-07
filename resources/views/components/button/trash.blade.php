@props([
    'label' => $attributes->get('label', 'Trash'),
    'callback' => $attributes->get('callback', 'trash'),
    'count' => $attributes->get('count', 1),
    'params' => $attributes->get('params'),
    'reload' => $attributes->get('reload', false),
    'inverted' => $attributes->get('inverted', true),
])

<x-button c="red" icon="trash-can" 
    :label="$label.($count > 1 ? ' ('.$count.')' : '')"
    :inverted="$inverted" 
    x-on:click="$dispatch('confirm', {
        title: '{{ __($attributes->get('title', 'atom::popup.confirm.trash.title')) }}',
        message: '{{ trans_choice($attributes->get('message', 'atom::popup.confirm.trash.message'), $count, ['count' => $count]) }}',
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
        'count',
        'message', 
        'callback', 
        'params', 
        'reload',
        'x-on:click',
    ]) }}/>
