@props([
    'label' => $attributes->get('label', 'Empty Trashed'),
    'title' => __($attributes->get('title', 'Empty Trashed')),
    'message' => __($attributes->get('message', 'Are you sure to clear all trashed records?')),
])

<x-button 
    c="red"
    icon="trash-can"
    :label="$label"
    x-on:click="$dispatch('confirm', {
        title: '{{ $title }}',
        message: '{{ $message }}',
        type: 'warning',
        onConfirmed: () => $wire.emptyTrashed().then(() => location.reload()),
    })"
    {{ $attributes->except('c', 'icon', 'label', 'title', 'message') }}
/>
