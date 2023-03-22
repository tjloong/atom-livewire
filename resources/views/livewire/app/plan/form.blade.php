<x-form header="Plan Information">
    <x-form.group cols="2">
        <x-form.text wire:model.defer="plan.name" label="Plan Name"/>
        <x-form.slug wire:model.defer="plan.slug" caption="Leave empty to auto generate" prefix="/"/>
        <x-form.number wire:model.defer="plan.trial"  label="Trial Period" postfix="days"/>
        <x-form.text wire:model.defer="plan.excerpt"/>
    </x-form.group>

    <x-form.group>
        <x-form.textarea wire:model.defer="plan.features" caption="Each line will be converted to a bullet point."/>
    </x-form.group>

    <x-form.group cols="2">
        <x-form.text wire:model.defer="plan.cta" label="CTA Text"/>
        <x-form.text wire:model.defer="plan.payment_description" caption="This will appear as the line item description when user checkout."/>
        <x-form.select wire:model="inputs.upgradables" label="Upgradable To"
            :options="data_get($this->options, 'plans')"
            multiple
        />
        <x-form.select wire:model="inputs.downgradables" label="Downgradable To"
            :options="data_get($this->options, 'plans')"
            multiple
        />
    </x-form.group>

    <x-form.group>
        <x-form.checkbox label="Plan is active" wire:model="plan.is_active"/>
    </x-form.group>
</x-form>
