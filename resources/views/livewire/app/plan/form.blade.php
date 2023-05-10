<div class="w-full">

    <x-form>
        <x-form.group cols="2">
            <x-form.text wire:model.lazy="plan.name" label="Plan Name"/>
            <x-form.text wire:model.defer="plan.code"/>
            <x-form.text wire:model.defer="plan.description"/>
            <x-form.select.country wire:model="plan.country"/>
            <x-form.select.currency wire:model="plan.currency"/>
            <x-form.number wire:model.defer="plan.trial" label="Trial Period" postfix="days"/>
            <x-form.select wire:model="inputs.upgrades" :options="data_get($this->options, 'upgrades')" multiple/>
        </x-form.group>
    
        @if ($plan->exists)
            <x-form.group label="Prices">
                <x-form.items :label="false" :data="$plan->prices->map(fn($price) => [
                    [
                        'name' => 'Price Code',
                        'label' => $price->code,
                        'emitTo' => [lw('app.plan.price-modal'), 'open', ['id' => $price->id]],
                    ],
                    [
                        'name' => 'Amount',
                        'class' => 'text-right',
                        'amount' => $price->amount,
                    ],
                    [
                        'name' => 'Status',
                        'class' => 'text-right',
                        'status' => $price->is_active ? 'active' : 'inactive',
                    ],
                ])">
                    <x-slot:button label="Add Price" 
                        wire:click="$emitTo('{{ lw('app.plan.price-modal') }}', 'open', {{ json_encode(['plan_id' => $plan->id]) }})"
                    ></x-slot:button>
                </x-form.items>
            </x-form.group>
    
            <x-form.group label="Features">
                <x-form.textarea wire:model.defer="inputs.features" caption="Each line will be converted to a bullet point." :label="false"/>
            </x-form.group>
    
            <x-form.group>
                <x-form.checkbox label="Active" wire:model="plan.is_active"/>
            </x-form.group>
        @endif
    </x-form>

    @livewire(lw('app.plan.price-modal'))
</div>