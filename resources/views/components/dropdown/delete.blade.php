@props([
    'label' => $attributes->get('label', 'Delete'),
    'count' => $attributes->get('count', 1),
    'callback' => $attributes->get('callback', 'delete'),
    'params' => $attributes->get('params'),
    'reload' => $attributes->get('reload', false),
])

<x-dropdown.item :label="$label" icon="delete" x-on:click="$dispatch('confirm', {
    title: '{{ __($attributes->get('title', 'atom::popup.confirm.delete.title')) }}',
    message: '{{ trans_choice($attributes->get('message', 'atom::popup.confirm.delete.message'), $count) }}',
    type: 'error',
    onConfirmed: () => {
        $wire.call('{{ $callback }}', {{ json_encode($params) }})
            .then(() => {{ json_encode($reload) }} && location.reload())
    },
})"/>
