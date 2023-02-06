<x-button
    {{ $attributes->except(['title', 'message', 'callback', 'params']) }}
    x-on:click="$dispatch('confirm', {
        title: '{{ __($attributes->get('title', 'Confirm')) }}',
        message: '{{ __($attributes->get('message', 'Are you sure?')) }}',
        type: '{{ $attributes->get('type', 'info') }}',
        buttonText: {{ json_encode($attributes->get('button-text', [])) }},
        onConfirmed: () => $wire.call(
            '{{ $attributes->get('callback') }}', 
            '{{ $attributes->get('params') }}'
        ),
    })"
/>
