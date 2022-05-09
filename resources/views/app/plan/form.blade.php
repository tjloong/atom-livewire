<x-form>
    <x-form.text 
        label="Plan Name"
        wire:model.defer="plan.name" :error="$errors->first('plan.name')" required
    />

    <x-form.slug 
        label="Slug (Leave empty to auto generate)"
        wire:model.defer="plan.slug" prefix="/"
    />

    <x-form.number 
        label="Trial Period"
        wire:model.defer="plan.trial" unit="days"
    />

    <x-form.text 
        label="Excerpt"
        wire:model.defer="plan.excerpt"
    />

    <x-form.textarea 
        label="Features"
        wire:model.defer="features" caption="Each line will be converted to a bullet point."
    />

    <x-form.text 
        label="CTA Text"
        wire:model.defer="plan.cta"
    />

    <x-form.tags 
        label="Upgradable To"
        wire:model.defer="upgradables" :options="$this->otherPlans"
    />

    <x-form.tags 
        label="Downgradable To"
        wire:model.defer="downgradables" :options="$this->otherPlans"
    />

    <x-form.checkbox 
        label="Plan is active"
        wire:model="plan.is_active"
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
