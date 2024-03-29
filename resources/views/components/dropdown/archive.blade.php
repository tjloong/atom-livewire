@php
    $label = $attributes->get('label', 'common.label.archive');
    $callback = $attributes->get('callback', 'archive');
    $params = $attributes->get('params');
@endphp

<x-dropdown.item :label="$label" icon="box-archive" 
    x-on:click="$wire.call('{{ $callback }}', {{ json_encode($params) }})"/>