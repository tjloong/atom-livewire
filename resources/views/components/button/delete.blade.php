@php
    $label = $attributes->get('label') ?? 'atom::common.button.delete';
    $params = $attributes->get('params');
    $reload = $attributes->get('reload', false);
    $inverted = $attributes->get('inverted', true);
    $callback = $attributes->get('callback', 'delete');
    $title = $attributes->get('title') ?? 'atom::common.alert.delete.title';
    $message = $attributes->get('message') ?? 'atom::common.alert.delete.message';
    
@endphp

<x-button c="red" icon="trash-can"
    :label="$label"
    :inverted="$inverted" 
    x-on:click="$dispatch('confirm', {
        title: '{{ __($title) }}',
        message: '{{ __($message) }}',
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
