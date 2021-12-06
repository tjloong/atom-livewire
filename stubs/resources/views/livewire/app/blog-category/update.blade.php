<div class="max-w-lg mx-auto">
    <x-page-header title="{{ $label->name }}" back>
        <x-button icon="trash" color="red" inverted x-on:click="$dispatch('confirm', {
            title: 'Delete Blog Category',
            message: 'Are you sure to delete this blog category?',
            type: 'error',
            onConfirmed: () => $wire.delete(),
        })">
            Delete
        </x-button>
    </x-page-header>

    @livewire('app.blog-category.form', ['label' => $label])
</div>