<x-button color="red" icon="trash-can" inverted 
    label="Empty Trashed"
    x-on:click="$dispatch('confirm', {
        title: '{{ __('Empty Trashed') }}',
        message: '{{ __('Are you sure to clear all trashed records?') }}',
        type: 'warning',
        onConfirmed: () => $wire.emptyTrashed().then(() => location.reload()),
    })"
/>
