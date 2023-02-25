<x-form>
    <div class="-m-6 flex flex-col divide-y">
        <x-box.row label="Plan">{{ $plan->name }}</x-box.row>

        @if ($this->readonly)
            <x-box.row label="Price">{{ currency($price->amount, $price->currency) }}</x-box.row>
            <x-box.row label="Expired After">{{ $price->is_lifetime ? 'Valid for lifetime' : $price->expired_after.' Months' }}</x-box.row>
        @endif

        <div class="p-6 grid gap-6 md:grid-cols-2">
            <x-form.select.country label="Available for Country"
                wire:model="price.country"
            />
    
            @if (!$this->readonly)
                <x-form.select.currency label="Currency"
                    wire:model="price.currency" 
                    :error="$errors->first('price.currency')"
                    required 
                />
    
                <x-form.number label="Price"
                    wire:model.defer="price.amount" 
                    :error="$errors->first('price.amount')"
                    required 
                />
    
                <x-form.number label="Expired After"
                    wire:model.defer="price.expired_after"
                    unit="Months"
                />
            @endif
    
            <x-form.number label="Discounted Amount"
                wire:model.defer="price.discount" 
                :error="$errors->first('price.discount')"
            />
    
            <x-form.text label="Shout Out Text"
                wire:model.defer="price.shoutout"
            />
        </div>

        <div class="p-6 grid gap-6 md:grid-cols-2">
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
            <div class="p-4">
                <x-alert>
                    <a class="flex items-center gap-1">
                        {{ __('This plan has :count subscribers', ['count' => $price->users->count()]) }}
                    </a>
                </x-alert>
            </div>
        @endif
    </div>

    <x-slot:foot>
        <x-button.submit label="Save Price"/>
    </x-slot:foot>
</x-form>