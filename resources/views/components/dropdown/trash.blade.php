@php
    $label = $attributes->get('label') ?? 'atom::common.button.trash';
    $count = $attributes->get('count', 1);
    $callback = $attributes->get('callback', 'trash');
    $params = $attributes->get('params');
    $reload = $attributes->get('reload', false);
    $title = $attributes->get('title') ?? 'atom::common.alert.trash.title';
    $message = $attributes->get('message') ?? 'atom::common.alert.trash.message';
@endphp

<x-dropdown.item :label="$label" icon="delete" x-on:click="$dispatch('confirm', {
    title: '{{ __($title) }}',
    message: '{{ trans_choice($message, $count) }}',
    type: 'error',
    onConfirmed: () => {
        $wire.call('{{ $callback }}', {{ json_encode($params) }})
            .then(() => {{ json_encode($reload) }} && location.reload())
    },
})"/>
