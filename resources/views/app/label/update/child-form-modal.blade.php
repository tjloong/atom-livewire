<x-modal uid="child-form-modal" :header="(data_get($child, 'id') ? 'Update' : 'Create').' Label Child'">
    <form wire:submit.prevent="submit" class="grid gap-4">
        @foreach ($locales->sort() as $locale)
            <x-form.text
                label="Child Label Name"
                :label-tag="$locales->count() > 1 ? data_get(metadata()->locales($locale), 'name') : null"
                wire:model.defer="child.name.{{ $locale }}"
                :error="$errors->first('child.name.'.$locale)"
                required
            />
        @endforeach

        <x-form.slug 
            label="Child Label Slug"
            wire:model.defer="child.slug" 
            prefix="/"
            caption="Leave empty to auto generate"
            :error="$errors->first('child.slug')"
        />

        <x-button.submit/>
    </form>
</x-modal>
