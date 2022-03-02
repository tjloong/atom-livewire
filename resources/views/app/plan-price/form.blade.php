<form wire:submit.prevent="submit" class="grid gap-4">
    <div class="bg-white rounded-md shadow p-4 flex gap-2">
        <x-icon name="info-circle" class="text-blue-400" size="20px"/>

        <x-input.field>
            <x-slot name="label">Plan</x-slot>
            {{ $plan->name }}
        </x-input.field>
    </div>

    <x-box>
        <div class="p-5">
            <x-input.currency wire:model="price.currency" required>
                Currency
            </x-input.currency>

            <x-input.number wire:model.defer="price.amount" required>
                Price
            </x-input.number>
    
            <x-input.select
                wire:model="price.recurring"
                required
                :options="[
                    ['value' => 'monthly', 'label' => 'Monthly'],
                    ['value' => 'yearly', 'label' => 'Yearly'],
                    ['value' => 'one-off', 'label' => 'One-Off'],
                ]"
            >
                Recurring Period
            </x-input.select>

            <x-input.country wire:model="price.country">
                Available for Country
            </x-input.country>

            <x-input.checkbox wire:model="price.is_default">
                Use this price as default (if there are multiple prices).
            </x-input.checkbox>
        </div>

        <x-slot name="buttons">
            <x-button color="green" icon="check" type="submit">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>