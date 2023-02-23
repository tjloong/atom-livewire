<x-modal uid="tax-form-modal" :header="data_get($tax, 'id') ? 'Update Tax' : 'Create Tax'">
    @if ($tax)
        <div class="grid gap-6">
            <x-form.text 
                label="Tax Name"
                wire:model.defer="tax.name" 
                :error="$errors->first('tax.name')" 
                required
            />

            <x-form.select
                label="Country"
                wire:model="tax.country"
                :options="metadata()->countries()"
                required
            />

            <x-form.text
                label="Region"
                wire:model.defer="tax.region"
            />

            <x-form.number 
                label="Tax Rate"
                wire:model.defer="tax.rate"
                step="0.01"
                min="0"
                unit="%"
                required
            />

            <x-form.checkbox 
                label="Tax is active"
                wire:model="tax.is_active"
            />
        </div>

        <x-slot:foot>
            <x-button.submit type="button"
                wire:click="submit"
            />
        </x-slot:foot>
    @endif
</x-modal>
