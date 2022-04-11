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

            @if ($this->readonly)
                <x-input.field>
                    <x-slot:label>Currency</x-slot:label>
                    {{ $price->currency }}
                </x-input.field>

                <x-input.field>
                    <x-slot:label>Price</x-slot:label>
                    {{ currency($price->amount) }}
                </x-input.field>

                <x-input.field>
                    <x-slot:label>Expired After</x-slot:label>
                    {{ $price->is_lifetime ? 'Valid for lifetime' : $price->expired_after }}
                </x-input.field>
            @else
                <x-input.currency wire:model="price.currency" required :error="$errors->first('price.currency')">
                    Currency
                </x-input.currency>

                <x-input.number wire:model.defer="price.amount" required :error="$errors->first('price.amount')">
                    Price
                </x-input.number>

                <x-input.field required :error="$errors->first('price.expired_after')">
                    <x-slot name="label">Expired After</x-slot>
                    <div class="grid gap-2">
                        <div x-data="{ isLifetime: @entangle('price.is_lifetime') }" x-show="!isLifetime">
                            <x-input.number wire:model.defer="price.expired_after" :units="['day', 'month', 'year']"/>
                        </div>

                        <div>
                            <x-input.checkbox wire:model="price.is_lifetime">
                                Valid for lifetime
                            </x-input.checkbox>
                        </div>
                    </div>
                </x-input.field>
            @endif

            <x-input.number wire:model.defer="price.discount" :error="$errors->first('price.discount')">
                Discounted Amount
            </x-input.number>

            <x-input.text wire:model.defer="price.shoutout">
                Shout Out Text
            </x-input.text>

            <div class="grid gap-4">
                <x-input.checkbox wire:model="price.is_default">
                    Use this price as default (if there are multiple prices).
                </x-input.checkbox>

                <a class="flex items-center gap-1">
                    <x-icon name="info-circle" class="text-gray-400" size="20px"/>
                    {{ $price->accounts->count() }} {{ str('user')->plural($price->accounts->count()) }} subscribed to this price.
                </a>
            </div>
        </div>

        <x-slot name="buttons">
            <x-button color="green" icon="check" type="submit">
                Save Price
            </x-button>
        </x-slot>
    </x-box>
</form>