<form wire:submit.prevent="save">
    <x-box>
        <div class="p-5">
            <x-input.text wire:model.defer="label.name">
                Category Name
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green" wire:loading.class="loading">
                Save Blog Category
            </x-button>
        </x-slot>
    </x-box>
</form>