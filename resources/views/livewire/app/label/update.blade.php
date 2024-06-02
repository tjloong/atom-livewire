<x-form.drawer class="max-w-screen-sm">
@if ($label)
    @if ($label->exists)
        <x-slot:buttons :delete="!$label->is_locked"></x-slot:buttons>
        <x-slot:heading title="app.label.update-label"></x-slot:heading>
    @else
        <x-slot:heading title="app.label.create-label"></x-slot:heading>
    @endif

    <x-group>
        @if ($type = $label->type)
            <x-form.text :value="str()->headline($type)" label="app.label.type" readonly/>
        @endif

        @if ($label->is_locked)
            <x-form.field label="app.label.label">
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
                <x-form.field label="app.label.parent" :value="$label->parents
                    ->map(fn($parent) => $parent->locale('name'))
                    ->join(' / ')"/>
            @endif
        @else
            <x-form.field label="app.label.name" required>
                <div class="flex flex-col gap-2">
                    @foreach ($this->locales->sort() as $locale)
                        <x-form.text :label="false"
                            wire:model.defer="inputs.name.{{ $locale }}"
                            :prefix="$this->locales->count() > 1 ? $locale : null"/>
                    @endforeach
                </div>
            </x-form.field>

            <x-form.text wire:model.defer="label.slug" label="app.label.slug" placeholder="autogen" prefix="/"/>

            <x-form.select.label wire:model="label.parent_id" label="app.label.parent" :type="$type" children>
                <x-slot:foot></x-slot:foot>
            </x-form.select.label>

            <x-form.color wire:model="label.color" label="app.label.color"/>
            <x-form.file wire:model="label.image_id" label="app.label.image"/>
        @endif
    </x-group>
@endif
</x-form.drawer>
    