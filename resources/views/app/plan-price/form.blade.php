<form wire:submit.prevent="submit" class="grid gap-4">
    <x-box>
        <div class="p-5">
            <x-input.field>
                <x-slot name="label">Plan</x-slot>
                <div class="font-semibold">{{ $plan->name }}</div>
                <div class="font-medium text-gray-500">{{ $plan->excerpt }}</div>
            </x-input.field>

            <x-input.country wire:model="price.country">
                Available for Country
            </x-input.country>

            <x-input.currency wire:model="price.currency" required :error="$errors->first('price.currency')" :disabled="$readonly">
                Currency
            </x-input.currency>

            <x-input.number wire:model.defer="price.amount" required :error="$errors->first('price.amount')" :disabled="$readonly">
                Price
            </x-input.number>

            <x-input.field required :error="$errors->first('price.expired_after')">
                <x-slot name="label">Expired After</x-slot>
                <div class="grid gap-1">
                    <div x-data="{ isLifetime: @entangle('price.is_lifetime') }" x-show="!isLifetime">
                        <x-input.number wire:model.defer="price.expired_after" :units="['day', 'month', 'year']" :disabled="$readonly"/>
                    </div>

                    <x-input.checkbox wire:model="price.is_lifetime" :disabled="$readonly">
                        Valid for lifetime
                    </x-input.checkbox>
                </div>
            </x-input.field>

            <x-input.text wire:model.defer="price.shoutout">
                Shout Out Text
            </x-input.text>

            <x-input.field>
                <x-input.checkbox wire:model="price.is_default">
                    Use this price as default (if there are multiple prices).
                </x-input.checkbox>
            </x-input.field>

            <a class="flex items-center gap-1">
                <x-icon name="info-circle" class="text-gray-400" size="20px"/>
                {{ $price->accounts->count() }} {{ str('user')->plural($price->accounts->count()) }} subscribed to this price.
            </a>
        </div>

        <x-slot name="buttons">
            <x-button color="green" icon="check" type="submit">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>