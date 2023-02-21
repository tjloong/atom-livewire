<x-form>
    <div class="grid gap-6 md:grid-cols-2">
        <x-form.text label="Person Name"
            wire:model.defer="person.name"
            :error="$errors->first('person.name')"
            required
        />

        <x-form.select.label label="Salutation" type="salutation"
            wire:model.defer="person.salutation"
        />

        <x-form.text label="Email"
            wire:model.defer="person.email"
        />

        <x-form.text label="Phone"
            wire:model.defer="person.phone"
        />

        <x-form.text label="Designation"
            wire:model.defer="person.designation"
        />
    </div>
        
    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
