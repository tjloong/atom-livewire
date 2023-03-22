<x-form>
    <div class="flex flex-col divide-y">
        <x-field label="Plan" :value="$plan->name"/>

        @if ($this->readonly)
            <x-field label="Price" :value="currency($price->amount, $price->currency)"/>
            <x-field label="Expired After" :value="$price->is_lifetime ? 'Valid for lifetime' : $price->expired_after.' Months'"/>
        @endif
    </div>

    <x-form.group cols="2">
        <x-form.select.country wire:model="price.country" label="Available for Country"/>

        @if (!$this->readonly)
            <x-form.select.currency wire:model="price.currency"/>
            <x-form.number wire:model.defer="price.amount" label="Price"/>
            <x-form.number wire:model.defer="price.expired_after" postfix="Months"/>
        @endif

        <x-form.number wire:model.defer="price.discount" label="Discounted Amount"/>
        <x-form.text wire:model.defer="price.shoutout" label="Shout Out Text"/>
    </x-form.group>

    <x-form.group>
        <div class="grid">
            <x-form.checkbox wire:model="price.is_default"
                label="Use this price as default"
                caption="If there are multiple prices."
            />
        
            @if ($this->enabledStripe)
                <x-form.checkbox wire:model="price.is_recurring"
                    label="Enable recurring billing"
                    caption="Only available with Stripe payment."
                />
            @endif
        </div>
    </x-form.group>

    @if ($price->exists)
        <x-form.group>
            <x-alert>
                <a class="flex items-center gap-1">
                    {{ __('This plan has :count subscribers', ['count' => $price->users->count()]) }}
                </a>
            </x-alert>
        </x-form.group>
    @endif
</x-form>