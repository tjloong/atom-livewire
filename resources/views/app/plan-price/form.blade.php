<x-form class="grid gap-4">
    <x-form.field label="Plan">
        <div class="font-semibold">{{ $plan->name }}</div>
        <div class="font-medium text-gray-500">{{ $plan->excerpt }}</div>
    </x-form.field>

    <x-form.picker 
        label="Available for Country"
        wire:model="planPrice.country"
        options="country"
        :selected="$planPrice->country"
    />

    @if ($this->readonly)
        <x-form.field label="Currency">
            {{ $planPrice->currency }}
        </x-form.field>

        <x-form.field label="Price">
            {{ currency($planPrice->amount) }}
        </x-form.field>

        <x-form.field label="Expired After">
            {{ $planPrice->is_lifetime ? 'Valid for lifetime' : $planPrice->expired_after.' Months' }}
        </x-form.field>
    @else
        <x-form.currency 
            label="Currency"
            wire:model="planPrice.currency" 
            :error="$errors->first('planPrice.currency')"
            required 
        />

        <x-form.number 
            label="Price"
            wire:model.defer="planPrice.amount" 
            :error="$errors->first('planPrice.amount')"
            required 
        />

        <x-form.field 
            label="Expired After" 
            :error="$errors->first('planPrice.expired_after')"
            required 
        >
            <div class="grid gap-2">
                <div x-data="{ isLifetime: @entangle('planPrice.is_lifetime') }" x-show="!isLifetime">
                    <x-form.number wire:model.defer="planPrice.expired_after" unit="Months"/>
                </div>

                <div>
                    <x-form.checkbox 
                        label="Valid for lifetime"
                        wire:model="planPrice.is_lifetime"
                    />
                </div>
            </div>
        </x-form.field>
    @endif

    <x-form.number 
        label="Discounted Amount"
        wire:model.defer="planPrice.discount" 
        :error="$errors->first('planPrice.discount')"
    />

    <x-form.text 
        label="Shout Out Text"
        wire:model.defer="planPrice.shoutout"
    />

    <div class="grid gap-4">
        <x-form.checkbox 
            label="Use this price as default (if there are multiple prices)."
            wire:model="planPrice.is_default"
        />

        <a class="flex items-center gap-1">
            <x-icon name="info-circle" class="text-gray-400" size="20px"/>
            {{ $planPrice->accounts->count() }} {{ str('user')->plural($planPrice->accounts->count()) }} subscribed to this planPrice.
        </a>
    </div>

    <x-slot:foot>
        <x-button.submit label="Save Price"/>
    </x-slot:foot>
</x-form>