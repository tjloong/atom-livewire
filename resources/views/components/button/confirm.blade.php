<x-button
    {{ $attributes->except(['title', 'message', 'buttons', 'callback', 'params']) }}
    x-on:click="$dispatch('confirm', {
        title: '{{ __($attributes->get('title', 'Confirm')) }}',
        message: '{{ __($attributes->get('message', 'Are you sure?')) }}',
        type: '{{ $attributes->get('type', 'info') }}',
        buttons: {{ json_encode($attributes->get('buttons', [])) }},
        onConfirmed: () => $wire.call(
            '{{ $attributes->get('callback') }}', 
            '{{ $attributes->get('params') }}'
        ),
    })"
/>
