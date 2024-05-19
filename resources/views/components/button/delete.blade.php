@php
$label = $attributes->get('label') ?? 'app.label.delete';
$params = $attributes->get('params');
$reload = $attributes->get('reload', false);
$inverted = $attributes->get('inverted', true);
$callback = $attributes->get('callback', 'delete');
$title = $attributes->get('title') ?? 'app.alert.delete.title';
$message = $attributes->get('message') ?? 'app.alert.delete.message';
$delegate = $attributes->has('x-on:confirm-delete');
$except = ['c', 'icon', 'label', 'title', 'message', 'callback', 'params', 'reload', 'x-on:click'];
@endphp

<x-button color="red" icon="trash-can" :label="$label" :inverted="$inverted" x-on:click="$dispatch('confirm', {
    title: '{!!tr($title)!!}',
    message: '{!!tr($message)!!}',
    type: 'error',
    onConfirmed: () => {{Js::from($delegate)}}
        ? $dispatch('confirm-delete')
        : $wire.call({{Js::from($callback)}}, {{Js::from($params)}}).then(() => {
            {{Js::from($reload)}} && location.reload()
        }),
})" {{ $attributes->except($except) }}>
</x-button>
