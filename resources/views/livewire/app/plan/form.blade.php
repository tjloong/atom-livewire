<x-form header="Plan Information">
    <div class="-m-6 flex flex-col divide-y">
        <div class="p-6 grid gap-6 md:grid-cols-2">
            <x-form.text label="Plan Name"
                wire:model.defer="plan.name" 
                :error="$errors->first('plan.name')" 
                required
            />

            <x-form.slug label="Slug (Leave empty to auto generate)"
                wire:model.defer="plan.slug" 
                prefix="/"
            />

            <x-form.number label="Trial Period"
                wire:model.defer="plan.trial" 
                unit="days"
            />

            <x-form.text label="Excerpt"
                wire:model.defer="plan.excerpt"
            />

            <x-form.textarea label="Features"
                wire:model.defer="plan.features"
                caption="Each line will be converted to a bullet point."
            />
            <x-form.text label="CTA Text"
                wire:model.defer="plan.cta"
            />

            <x-form.text
                label="Payment Description"
                wire:model.defer="plan.payment_description"
                caption="This will appear as the line item description when user checkout."
            />
        </div>

        <div class="p-6 grid gap-6 md:grid-cols-2">
            <x-form.select label="Upgradable To"
                wire:model="upgradables" 
                :options="$this->otherPlans"
                multiple
            />
        
            <x-form.select label="Downgradable To"
                wire:model="downgradables" 
                :options="$this->otherPlans"
                multiple
            />
        </div>

        <div class="p-4">
            <x-form.checkbox label="Plan is active" wire:model="plan.is_active"/>
        </div>
    </div>

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
