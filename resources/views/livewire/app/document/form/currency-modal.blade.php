<x-modal uid="currency-modal" header="Set Currency">
    @if ($inputs)
        <div class="flex flex-col gap-6">
            <x-form.select label="Currency"
                wire:model="inputs.currency"
                :options="collect($currencies)->pluck('currency')->flatten()->values()"
                :error="$errors->first('inputs.currency')"
                required
            />
    
            @if ($this->isForeignCurrency)
                <x-form.amount label="Rate"
                    wire:model.defer="inputs.currency_rate"
                    placeholder="Conversion Rate"
                />
            @endif
        </div>

        <x-slot:foot>
            <x-button.submit type="button"
                label="Set Currency"
                wire:click="submit"
            />
        </x-slot:foot>
    @endif
</x-modal>