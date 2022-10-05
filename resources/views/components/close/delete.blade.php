<x-close color="red"
    x-on:click="$dispatch('confirm', {
        title: '{{ __($attributes->get('title')) }}',
        message: '{{ __($attributes->get('message')) }}',
        type: 'error',
        onConfirmed: () => $wire.call(
            '{{ $attributes->get('callback', 'delete') }}', 
            {{ json_encode($attributes->get('params')) }}
        ),
    })"
/>
