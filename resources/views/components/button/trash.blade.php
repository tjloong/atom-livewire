@php
    $label = $attributes->get('label') ?? 'common.label.trash';
    $callback = $attributes->get('callback', 'trash');
    $count = $attributes->get('count', 1);
    $params = $attributes->get('params');
    $reload = $attributes->get('reload', false);
    $inverted = $attributes->get('inverted', true);
    $title = $attributes->get('title') ?? 'common.alert.trash.title';
    $message = $attributes->get('message') ?? 'common.alert.trash.message';
@endphp

<x-button c="red" icon="trash-can" 
    :label="$label.($count > 1 ? ' ('.$count.')' : '')"
    :inverted="$inverted" 
    x-on:click="$dispatch('confirm', {
        title: '{{ __($title) }}',
        message: '{{ trans_choice($message, $count, ['count' => $count]) }}',
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
