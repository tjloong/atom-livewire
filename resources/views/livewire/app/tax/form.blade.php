<x-form>
    <div class="grid gap-6 md:grid-cols-2">
        <x-form.text label="Tax Name"
            wire:model.defer="tax.name" 
            :error="$errors->first('tax.name')" 
            required
        />

        <x-form.number label="Tax Rate"
            wire:model.defer="tax.rate"
            step="0.01"
            min="0"
            unit="%"
            required
        />

        <x-form.select.country
            wire:model="tax.country"
            :error="$errors->first('tax.country')"
            required
        />

        <x-form.text label="Region"
            wire:model.defer="tax.region"
        />
    </div>

    <x-form.checkbox 
        label="Tax is active"
        wire:model="tax.is_active"
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
