<x-form header="Label Information">
    <x-form.field label="Label Type">
        {{ str()->headline(data_get($label, 'type')) }}
    </x-form.field>

    @foreach ($locales->sort() as $locale)
        <x-form.text
            label="Label Name"
            :label-tag="$this->locales->count() > 1 ? data_get(metadata()->locales($locale), 'name') : null"
            wire:model.defer="names.{{ $locale }}"
            :error="$errors->first('names.'.$locale)"
            required
        />
    @endforeach

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