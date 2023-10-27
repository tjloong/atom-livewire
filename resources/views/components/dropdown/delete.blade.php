@php    
    $label = $attributes->get('label') ?? 'common.label.delete';
    $count = $attributes->get('count', 1);
    $params = $attributes->get('params');
    $reload = $attributes->get('reload', false);
    $callback = $attributes->get('callback', 'delete');
    $title = $attributes->get('title') ?? 'common.alert.delete.title';
    $message = $attributes->get('message') ?? 'common.alert.delete.message';
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
