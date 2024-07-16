<x-drawer wire:submit.prevent="submit" wire:close="$emit('closeLabel')">
@if ($label)
    @if ($label->exists)
        <x-slot:heading title="app.label.edit-label"></x-slot:heading>
        <x-slot:buttons>
            <x-button action="submit"/>
            @if (!$label->is_locked) <x-button action="delete" no-label invert/> @endif
        </x-slot:buttons>
    @else
        <x-slot:heading title="app.label.create-label"></x-slot:heading>
        <x-slot:buttons>
            <x-button action="submit"/>
        </x-slot:buttons>
    @endif

    <x-fieldset inputs>
        @if ($label->type) <x-input :value="$label->type" label="app.label.type" apa readonly/> @endif

        @if ($label->is_locked)
            <x-field label="app.label.label" block>
                <div class="flex flex-col gap-2">
                    @foreach ($this->locales->sort() as $locale)
                        <div class="flex items-center gap-2">
                            @if ($this->locales->count() > 1) <x-badge :label="$locale"/> @endif
                            {{ data_get($label->name, $locale) }}
                        </div>
                    @endforeach
                </div>
            </x-field>

            @if ($label->parents->count())
                <x-field label="app.label.parent"
                    :value="$label->parents->map(fn($parent) => $parent->name_locale)->join(' / ')"
                    block>
                </x-field>
            @endif
        @else
            <x-field label="app.label.name" required block>
                <div class="flex flex-col gap-2">
                    @foreach ($this->locales->sort() as $locale)
                        <x-input
                            wire:model.defer="inputs.name.{{ $locale }}"
                            :prefix="$this->locales->count() > 1 ? $locale : null"
                            no-label>
                        </x-input>
                    @endforeach
                </div>
            </x-field>

            <x-input wire:model.defer="label.slug" label="app.label.slug" placeholder="autogen" prefix="/"/>
            <x-select wire:model="label.parent_id" label="app.label.parent" :options="'labels.'.$type"/>
            <x-color wire:model="label.color" label="app.label.color"/>
            <x-form.file wire:model="label.image_id" label="app.label.image"/>
        @endif
    </x-fieldset>
@endif
</x-drawer>
