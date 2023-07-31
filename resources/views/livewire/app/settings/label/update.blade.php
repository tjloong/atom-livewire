<x-form id="label-update" :header="optional($label)->exists ? 'Update Label' : 'Create Label'" drawer>
@if ($label)
    <x-slot:buttons>
        <x-button.submit size="sm"/>

        @if ($label->exists)
            <x-button.delete inverted size="sm" :label="false"
                title="Delete Label"
                message="Are you sure to DELETE this label?"
            />
        @endif
    </x-slot:buttons>

    <x-form.group>
        @if ($type = $label->type)
            <x-form.text :value="str()->headline($type)" label="Label Type" readonly/>
        @endif

        @if (data_get($label->data, 'is_locked'))
            <x-form.field label="Label Name">
                <div class="flex flex-col gap-2">
                    @foreach ($this->locales->sort() as $locale)
                        <div class="flex items-center gap-2">
                            @if ($this->locales->count() > 1) <x-badge :label="$locale"/> @endif
                            {{ data_get($label->name, $locale) }}
                        </div>
                    @endforeach
                </div>
            </x-form.field>

            @if ($label->parents->count())
                <x-form.field label="Parent" :value="$label->parents
                    ->map(fn($parent) => $parent->locale('name'))
                    ->join(' / ')"/>
            @endif
        @else
            <x-form.field label="Label Name" required>
                <div class="flex flex-col gap-2">
                    @foreach ($this->locales->sort() as $locale)
                        <x-form.text wire:model.defer="inputs.name.{{ $locale }}" :prefix="$this->locales->count() > 1 ? $locale : null" :label="false"/>
                    @endforeach
                </div>
            </x-form.field>

            <x-form.text wire:model.defer="label.slug" prefix="/" placeholder="autogen"/>
            <x-form.select.label wire:model="label.parent_id" :type="$type" children>
                <x-slot:foot></x-slot:foot>
            </x-form.select.label>
        @endif
    </x-form.group>
@endif
</x-form>
    