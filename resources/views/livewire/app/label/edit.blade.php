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

    <div class="p-5 flex flex-col gap-5">
        @if ($parent = $label->parent)
            <div class="flex items-center gap-3">
                <x-anchor :label="$parent->name_locale" wire:click="open({{ Js::from(['id' => $parent->id]) }})"/>
                <x-icon name="chevron-right" class="text-xs"/>
                <div class="font-medium text-gray-500">
                    {{ $label->exists ? $label->name_locale : tr('app.label.add-new') }}
                </div>
            </div>
        @endif

        <x-box>
            <x-fieldset>
                <x-field label="app.label.type" :value="str()->headline($label->type)"/>
                
                @if ($parent) <x-field label="app.label.parent" :value="$parent->name_locale"/> @endif
                
                @if ($label->is_locked) 
                    @foreach ($this->locales as $locale)
                        <x-field
                            :label="tr('app.label.label').($this->locales->count() > 1 ? ' ('.$locale.')' : '')"
                            :value="get($label->name, $locale)">
                        </x-field>
                    @endforeach

                    <x-field label="app.label.slug" :value="$label->slug"/>
                    <x-field label="app.label.color" :color="$label->color"/>
                    <x-field label="app.label.image" :image="$label->image"/>
                @else
                    @foreach ($this->locales as $locale)
                        <x-input
                            :label="tr('app.label.label').($this->locales->count() > 1 ? ' ('.$locale.')' : '')"
                            wire:model.defer="inputs.name.{{ $locale }}"
                            inline>
                        </x-input>
                    @endforeach

                    <x-input wire:model.defer="label.slug" label="app.label.slug" placeholder="autogen" prefix="/" inline/>
                    <x-select wire:model="label.parent_id" label="app.label.parent" :options="'labels.'.$type" inline/>
                    <x-color wire:model="label.color" label="app.label.color" inline/>
                    <x-form.file wire:model="label.image_id" label="app.label.image" inline/>        
                @endif
            </x-fieldset>
        </x-box>

        @if (!$label->parent_id)
            <div class="flex flex-col gap-2">
                <x-heading title="Sub-Labels" sm/>
                <x-flatbox>
                    <div class="flex flex-col divide-y">
                        @if (($children = $label->children()->get()) && $children->count())
                            <livewire:app.label.listing
                                :labels="$children"
                                :wire:key="$this->wirekey('children')">
                            </livewire:app.label.listing>
                        @endif

                        <x-anchor label="app.label.add-new" icon="add" align="center" class="py-2"
                            wire:click="open({{ Js::from([
                                'type' => $label->type,
                                'parent_id' => $label->id,
                            ]) }})">
                        </x-anchor>
                    </div>
                </x-flatbox>
            </div>
        @endif
    </div>
@endif
</x-drawer>
