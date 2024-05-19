@php
    $icon = $attributes->get('icon');
    $title = $attributes->get('title') ?? 'app.alert.delete.title';
    $message = $attributes->get('message') ?? 'app.alert.delete.message';
    $callback = $attributes->get('callback', 'delete');
    $params = $attributes->get('params');
    $delegate = $attributes->has('x-on:confirm-delete');
@endphp

<x-close color="red" :icon="$icon" x-on:click.stop="$dispatch('confirm', {
    title: '{!!tr($title)!!}',
    message: '{!!tr($message)!!}',
    type: 'error',
    onConfirmed: () => {{Js::from($delegate)}}
        ? $dispatch('confirm-delete')
        : $wire.call({{Js::from($callback)}}, {{Js::from($params)}}),
})" {{ $attributes->only('x-on:confirm-delete') }}/>
