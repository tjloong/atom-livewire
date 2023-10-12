@php
    $icon = $attributes->get('icon');
    $title = $attributes->get('title') ?? 'atom::common.alert.delete.title';
    $message = $attributes->get('message') ?? 'atom::common.alert.delete.message';
    $callback = $attributes->get('callback', 'delete');
    $params = $attributes->get('params');
@endphp

<x-close color="red" :icon="$icon" x-on:click.stop="$dispatch('confirm', {
    title: '{{ __($title) }}',
    message: '{{ __($message) }}',
    type: 'error',
    onConfirmed: () => $wire.call('{{ $callback }}', {{ json_encode($params) }}),
})"/>
