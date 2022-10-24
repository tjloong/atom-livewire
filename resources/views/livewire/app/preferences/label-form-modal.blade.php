<x-modal uid="label-form-modal" :header="data_get($label, 'id') ? 'Update Label' : 'Create Label'">
    @if ($label)
        <div class="grid gap-6">
            @if ($type = data_get($label, 'type'))
                <x-form.field label="Label Type">
                    {{ str()->headline($type) }}
                </x-form.field>
            @endif

            @if ($parent = $label->parent)
                <x-form.field label="Parent Label">
                    {{ $parent->locale('name') }}
                </x-form.field>
            @endif
        
            @if (data_get($label, 'data.is_locked'))
                @foreach ($this->locales->sort() as $locale)
                    <x-form.field
                        label="Label Name"
                        :label-tag="$this->locales->count() > 1 ? data_get(metadata()->locales($locale), 'name') : null"
                    >
                        {{ data_get($label, 'name.'.$locale) }}
                    </x-form.field>
                @endforeach
            @else
                @foreach ($this->locales->sort() as $locale)
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
            @endif
        </div>
    
        <x-slot:foot>
            <x-button.submit type="button"
                wire:click="submit"
            />
        </x-slot:foot>
    @endif
</x-modal>
