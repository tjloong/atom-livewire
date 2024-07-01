<x-form.drawer class="max-w-screen-sm">
@if ($label)
    @if ($label->exists)
        <x-slot:heading title="app.label.update-label"></x-slot:heading>
        <x-slot:buttons>
            <x-button action="submit"/>
            @if (!$label->is_locked) <x-button action="delete" no-label invert/> @endif
        </x-slot:buttons>
    @else
        <x-slot:heading title="app.label.create-label"></x-slot:heading>
    @endif

    <x-group>
        @if ($type = $label->type)
            <x-input :value="str()->headline($type)" label="app.label.type" readonly/>
        @endif

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
                    :value="$label->parents->map(fn($parent) => $parent->locale('name'))->join(' / ')"
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
            <x-form.color wire:model="label.color" label="app.label.color"/>
            <x-form.file wire:model="label.image_id" label="app.label.image"/>
        @endif
    </x-group>
@endif
</x-form.drawer>
    