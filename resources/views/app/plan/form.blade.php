<form wire:submit.prevent="submit">
    <x-box>
        <div class="p-5">
            <x-input.text wire:model.defer="plan.name" :error="$errors->first('plan.name')" required>
                Plan Name
            </x-input.text>

            <x-input.slug wire:model.defer="plan.slug" prefix="/">
                Slug (Leave empty to auto generate)
            </x-input.slug>

            <x-input.text wire:model.defer="plan.excerpt">
                Excerpt
            </x-input.text>

            <x-input.textarea wire:model.defer="features" caption="Each line will be converted to a bullet point.">
                Features
            </x-input.textarea>

            <x-input.text wire:model.defer="plan.data.cta">
                CTA Text
            </x-input.text>

            <x-input.checkbox wire:model="plan.is_active">
                Plan is active
            </x-input.checkbox>
        </div>

        <x-slot name="buttons">
            <div class="flex justify-between">
                <x-button type="submit" icon="check" color="green">
                    Save
                </x-button>
            </div>
        </x-slot>
    </x-box>
</form>
