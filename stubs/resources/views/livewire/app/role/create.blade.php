<div class="max-w-lg mx-auto">
    <x-page-header title="Create Role" back/>

    <form wire:submit.prevent="save">
        <x-box>
            <div class="p-5">
                <x-input.text wire:model.defer="role.name" :error="$errors->first('role.name')" required>
                    Role Name
                </x-input.text>
            </div>

            <x-slot name="buttons">
                <x-button type="submit" icon="check" color="green">
                    Create Role
                </x-button>
            </x-slot>
        </x-box>
    </form>
</div>