@php
    $type = $attributes->get('type', 'info');
    $buttons = $attributes->get('buttons', []);
    $callback = $attributes->get('callback');
    $params = $attributes->get('params');
    $href = $attributes->get('href');
    $title = $attributes->get('title', 'Confirm');
    $message = $attributes->get('message', 'Are you sure?');
@endphp

<x-button x-data="{
    prompt () {
        $dispatch('confirm', {
            title: '{{ tr($title) }}',
            message: '{{ tr($message) }}',
            type: '{{ $type }}',
            buttons: {{ json_encode($buttons) }},
            onConfirmed: () => {
                if ({{ json_encode($href ?? false) }}) window.location = '{{ $href }}';
                else if ({{ json_encode($callback ?? false) }}) $wire.call('{{ $callback }}', {{ json_encode($params) }});
            },
        })
    },
}" x-on:click="prompt()" {{ $attributes->except('title', 'message', 'buttons', 'callback', 'href', 'params') }}/>
