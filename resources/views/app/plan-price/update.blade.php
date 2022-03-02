<div class="max-w-lg mx-auto">
    <x-page-header title="Plan Price" back>
        <x-button icon="trash" color="red" inverted x-on:click="$dispatch('confirm', {
            title: 'Delete Plan Price',
            message: 'Are you sure to delete this price?',
            type: 'error',
            onConfirmed: () => $wire.delete(),
        })">
            Delete
        </x-button>
    </x-page-header>

    @livewire('atom.plan-price.form', compact('plan', 'price'))
</div>