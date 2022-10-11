<x-dropdown.item
    :label="$attributes->get('label', 'Delete')"
    :icon="$attributes->get('icon', 'delete')"
    class="cursor-pointer"
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
