<x-form class="grid gap-4">
    <x-form.field label="Plan">
        <div class="font-semibold">{{ $plan->name }}</div>
        <div class="font-medium text-gray-500">{{ $plan->excerpt }}</div>
    </x-form.field>

    <x-form.country 
        label="Available for Country"
        wire:model="price.country"
    />

    @if ($this->readonly)
        <x-form.field label="Currency">
            {{ $price->currency }}
        </x-form.field>

        <x-form.field label="Price">
            {{ currency($price->amount) }}
        </x-form.field>

        <x-form.field label="Expired After">
            {{ $price->is_lifetime ? 'Valid for lifetime' : $price->expired_after }}
        </x-form.field>
    @else
        <x-form.currency 
            label="Currency"
            wire:model="price.currency" 
            :error="$errors->first('price.currency')"
            required 
        />

        <x-form.number 
            label="Price"
            wire:model.defer="price.amount" 
            :error="$errors->first('price.amount')"
            required 
        />

        <x-form.field label="Expired After" required :error="$errors->first('price.expired_after')">
            <div class="grid gap-2">
                <div x-data="{ isLifetime: @entangle('price.is_lifetime') }" x-show="!isLifetime">
                    <x-form.number 
                        wire:model.defer="price.expired_after" 
                        :units="['day', 'month', 'year']"
                    />
                </div>

                <div>
                    <x-form.checkbox 
                        label="Valid for lifetime"
                        wire:model="price.is_lifetime"
                    />
                </div>
            </div>
        </x-form.field>
    @endif

    <x-form.number 
        label="Discounted Amount"
        wire:model.defer="price.discount" 
        :error="$errors->first('price.discount')"
    />

    <x-form.text 
        label="Shout Out Text"
        wire:model.defer="price.shoutout"
    />

    <div class="grid gap-4">
        <x-form.checkbox 
            label="Use this price as default (if there are multiple prices)."
            wire:model="price.is_default"
        />

        <a class="flex items-center gap-1">
            <x-icon name="info-circle" class="text-gray-400" size="20px"/>
            {{ $price->accounts->count() }} {{ str('user')->plural($price->accounts->count()) }} subscribed to this price.
        </a>
    </div>

    <x-slot:foot>
        <x-button.submit label="Save Price"/>
    </x-slot:foot>
</x-form>