<x-form.drawer id="label-update" class="max-w-screen-sm p-5">
@if ($label)
    @if ($label->exists)
        <x-slot:heading title="atom::label.heading.update"></x-slot:heading>
        <x-slot:buttons :delete="!$label->is_locked"></x-slot:buttons>
    @else
        <x-slot:heading title="atom::label.heading.create"></x-slot:heading>
    @endif

    <x-form.group class="p-0">
        @if ($type = $label->type)
            <x-form.text label="atom::label.label.type" readonly
                :value="str()->headline($type)"/>
        @endif

        @if ($label->is_locked)
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
            <x-form.field label="atom::label.label.name" required>
                <div class="flex flex-col gap-2">
                    @foreach ($this->locales->sort() as $locale)
                        <x-form.text :label="false"
                            wire:model.defer="inputs.name.{{ $locale }}"
                            :prefix="$this->locales->count() > 1 ? $locale : null"/>
                    @endforeach
                </div>
            </x-form.field>

            <x-form.text label="atom::label.label.slug" placeholder="autogen"
                wire:model.defer="label.slug" prefix="/"/>

            <x-form.select.label label="atom::label.label.parent" :type="$type" children
                wire:model="label.parent_id">
                <x-slot:foot></x-slot:foot>
            </x-form.select.label>

            <x-form.color label="atom::label.label.color"
                wire:model="label.color"/>

            <x-form.file label="atom::label.label.image"
                wire:model="label.image_id"/>
        @endif
    </x-form.group>
@endif
</x-form.drawer>
    