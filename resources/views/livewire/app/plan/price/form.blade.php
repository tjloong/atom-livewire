<x-form class="grid gap-4">
    <x-form.field label="Plan">
        <div class="font-semibold">{{ $plan->name }}</div>
        <div class="font-medium text-gray-500">{{ $plan->excerpt }}</div>
    </x-form.field>

    <x-form.select 
        label="Available for Country"
        wire:model="price.country"
        :options="metadata()->countries()"
    />

    @if ($this->readonly)
        <x-form.field label="Currency">
            {{ $price->currency }}
        </x-form.field>

        <x-form.field label="Price">
            {{ currency($price->amount) }}
        </x-form.field>

        <x-form.field label="Expired After">
            {{ $price->is_lifetime ? 'Valid for lifetime' : $price->expired_after.' Months' }}
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

        <x-form.number label="Expired After"
            wire:model.defer="price.expired_after"
            unit="Months"
        />
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
        <div class="grid gap-2">
            <x-form.checkbox 
                label="Use this price as default"
                caption="If there are multiple prices."
                wire:model="price.is_default"
            />
    
            @if ($this->enabledStripe)
                <x-form.checkbox
                    label="Enable recurring billing"
                    caption="Only available with Stripe payment."
                    wire:model="price.is_recurring"
                />
            @endif
        </div>

        @if ($price->exists)
            <x-alert>
                <a class="flex items-center gap-1">
                    {{ __('This plan has :count subscribers', ['count' => $price->accounts->count()]) }}
                </a>
            </x-alert>
        @endif
    </div>

    <x-slot:foot>
        <x-button.submit label="Save Price"/>
    </x-slot:foot>
</x-form>