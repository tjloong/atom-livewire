<div class="max-w-lg mx-auto">
    <x-page-header title="{{ $label->name }}" back>
        <x-button icon="trash" color="red" inverted x-on:click="$dispatch('confirm', {
            title: 'Delete Label',
            message: 'Are you sure to delete this label?',
            type: 'error',
            onConfirmed: () => $wire.delete(),
        })">
            Delete
        </x-button>
    </x-page-header>

    @livewire($this->component_name, ['label' => $label])
</div>