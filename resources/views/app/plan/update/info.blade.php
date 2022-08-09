<x-form header="Plan Information">
    <x-form.text 
        label="Plan Name"
        wire:model.defer="plan.name" 
        :error="$errors->first('plan.name')" 
        required
    />

    <x-form.slug 
        label="Slug (Leave empty to auto generate)"
        wire:model.defer="plan.slug" 
        prefix="/"
    />

    <x-form.number 
        label="Trial Period"
        wire:model.defer="plan.trial" 
        unit="days"
    />

    <x-form.text 
        label="Excerpt"
        wire:model.defer="plan.excerpt"
    />

    <x-form.textarea 
        label="Features"
        wire:model.defer="features"
        caption="Each line will be converted to a bullet point."
    />

    <x-form.text
        label="Payment Description"
        wire:model.defer="plan.payment_description"
        caption="This will appear as the line item description when user checkout."
    />

    <x-form.text 
        label="CTA Text"
        wire:model.defer="plan.cta"
    />

    <x-form.picker 
        label="Upgradable To"
        wire:model="upgradables" 
        :options="data_get($this->options, 'upgradables')"
        :selected="$upgradables"
        multiple
    />

    <x-form.picker 
        label="Downgradable To"
        wire:model="downgradables" 
        :options="data_get($this->options, 'downgradables')"
        :selected="$downgradables"
        multiple
    />

    <x-form.checkbox 
        label="Plan is active"
        wire:model="plan.is_active"
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
