@props([
    'icon' => $attributes->get('icon'),
    'title' => $attributes->get('title')
        ? $attributes->get('title')
        : __('atom::popup.confirm.delete.title'),
    'message' => $attributes->get('message')
        ? $attributes->get('message')
        : __('atom::popup.confirm.delete.message'),
    'callback' => $attributes->get('callback', 'delete'),
    'params' => $attributes->get('params'),
])

<x-close color="red" :icon="$icon" x-on:click.stop="$dispatch('confirm', {
    title: '{{ $title }}',
    message: '{{ $message }}',
    type: 'error',
    onConfirmed: () => $wire.call('{{ $callback }}', {{ json_encode($params) }}),
})"/>
