@props([
    'label' => $attributes->get('label', 'Trash'),
    'title' => $attributes->get('title')
        ? __($attributes->get('title'))
        : __('atom::popup.confirm.trash.title'),
    'message' => $attributes->get('message')
        ? __($attributes->get('message'))
        : __('atom::popup.confirm.trash.message'),
    'callback' => $attributes->get('callback', 'trash'),
    'params' => $attributes->get('params'),
    'reload' => $attributes->get('reload', false),
])

<x-dropdown.item :label="$label" icon="delete" x-on:click="$dispatch('confirm', {
    title: '{{ $title }}',
    message: '{{ $message }}',
    type: 'error',
    onConfirmed: () => {
        $wire.call('{{ $callback }}', {{ json_encode($params) }})
            .then(() => {{ json_encode($reload) }} && location.reload())
    },
})"/>
