<x-form header="Label Information">
    <x-form.field label="Label Type">
        {{ str()->headline($label->type) }}
    </x-form.field>

    <x-form.text 
        label="Label Name"
        wire:model.defer="label.name" 
        :error="$errors->first('label.name')" 
        required
    />

    <x-form.slug 
        label="Label Slug"
        wire:model.defer="label.slug" 
        prefix="/"
        caption="Leave empty to auto generate"
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>