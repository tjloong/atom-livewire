<x-button 
    c="red"
    icon="trash-can"
    :label="$attributes->get('label', 'Delete')"
    x-on:click="$dispatch('confirm', {
        title: '{{ __($attributes->get('title', 'Delete')) }}',
        message: '{{ __($attributes->get('message', 'Are you sure?')) }}',
        type: 'error',
        onConfirmed: () => {
            $wire
                .call('{{ $attributes->get('callback', 'delete') }}', {{ json_encode($attributes->get('params')) }})
                .then(() => {{ json_encode($attributes->get('reload', false)) }} && location.reload())
        },
    })"
    {{ $attributes->except('c', 'icon', 'label', 'x-on:click') }}
/>
