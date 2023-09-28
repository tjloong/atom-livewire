@props([
    'label' => $attributes->get('label', 'Trash'),
    'count' => $attributes->get('count', 1),
    'callback' => $attributes->get('callback', 'trash'),
    'params' => $attributes->get('params'),
    'reload' => $attributes->get('reload', false),
])

<x-dropdown.item :label="$label" icon="delete" x-on:click="$dispatch('confirm', {
    title: '{{ __($attributes->get('title', 'atom::popup.confirm.trash.title')) }}',
    message: '{{ trans_choice($attributes->get('message', 'atom::popup.confirm.trash.message'), $count) }}',
    type: 'error',
    onConfirmed: () => {
        $wire.call('{{ $callback }}', {{ json_encode($params) }})
            .then(() => {{ json_encode($reload) }} && location.reload())
    },
})"/>
