<x-button 
    color="red"
    icon="trash-can"
    x-on:click="$dispatch('confirm', {
        title: '{{ __($attributes->get('title', 'Delete')) }}',
        message: '{{ __($attributes->get('message', 'Are you sure?')) }}',
        type: 'error',
        onConfirmed: () => $wire.delete({{ json_encode($attributes->get('params') ?? null) }})
            .then(() => {
                {{ json_encode($attributes->get('reload') ?? false) }} && location.reload()
            }),
    })"
    :label="$attributes->get('label', 'Delete')"
    {{ $attributes->only(['inverted', 'size']) }}
/>
