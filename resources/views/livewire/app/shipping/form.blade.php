<x-form>
    <x-form.group cols="2">
        <x-form.text wire:model.defer="rate.name"/>
        <x-form.number wire:model.defer="rate.price" step=".01"/>
        <x-form.select.country wire:model="inputs.countries" multiple/>
    </x-form.group>

    <x-form.group cols="2" label="Base on Condition">
        <x-form.select wire:model="rate.condition" :options="[
            ['value' => 'weight', 'label' => 'Item Weight'],
            ['value' => 'amount', 'label' => 'Order Amount'],
        ]"/>
        <div></div>

        @if ($condition = $rate->condition)
            <x-form.number wire:model.defer="rate.min" :postfix="$condition === 'weight' ? 'kg' : (tenant('default_currency') ?? settings('default_currency'))"/>
            <x-form.number wire:model.defer="rate.max" :postfix="$condition === 'weight' ? 'kg' : (tenant('default_currency') ?? settings('default_currency'))"/>
        @endif
    </x-form.group>
</x-form>