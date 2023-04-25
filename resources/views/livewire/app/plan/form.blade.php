<x-form>
    <x-form.group cols="2">
        <x-form.text wire:model.defer="plan.code"/>
        <x-form.text wire:model.defer="plan.name" label="Plan Name"/>
        <x-form.text wire:model.defer="plan.description"/>
        <x-form.text wire:model.defer="plan.invoice_description" caption="This will appear as the line item description when user checkout."/>
        <x-form.select.country wire:model="plan.country"/>
        <x-form.select.currency wire:model="plan.currency"/>
        <x-form.number wire:model.defer="plan.price" step=".01"/>
        <x-form.text wire:model.defer="plan.valid" label="Valid Period" caption="eg. weekly / monthly / yearly / 14 days / 6 months"/>
        <x-form.select wire:model="plan.trial_plan_id" :options="data_get($this->options, 'trial_plans')"/>
    </x-form.group>

    <x-form.group>
        <x-form.textarea wire:model.defer="plan.features" caption="Each line will be converted to a bullet point."/>
    </x-form.group>

    <x-form.group>
        <div>
            <x-form.checkbox label="Recurring" wire:model="plan.is_recurring"/>
            <x-form.checkbox label="Hidden" wire:model="plan.is_hidden"/>
            <x-form.checkbox label="Active" wire:model="plan.is_active"/>
        </div>
    </x-form.group>
</x-form>
