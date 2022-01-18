<form wire:submit.prevent="save">
    <x-box>
        <div class="p-5">
            <x-input.field>
                <x-slot name="label">Label Type</x-slot>
                {{ Str::headline($label->type) }}
            </x-input.field>

            <x-input.text wire:model.defer="label.name" :error="$errors->first('label.name')" required>
                Label Name
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green">
                Save Label
            </x-button>
        </x-slot>
    </x-box>
</form>