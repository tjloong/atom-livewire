<x-form>
    <x-form.group>
        @if (count($this->parentTrails))
            <x-form.field label="Parent Label">
                <div class="flex flex-wrap items-center gap-2">
                    @foreach ($this->parentTrails as $i => $trail)
                        <div class="shrink-0">{{ $trail }}</div>
                        @if ($i !== array_key_last($this->parentTrails)) <x-icon name="arrow-right"/> @endif
                    @endforeach
                </div>
            </x-form.field>
        @endif

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
        @else
            <x-form.field label="Label Name" required>
                <div class="flex flex-col gap-2">
                    @foreach ($this->locales->sort() as $locale)
                        <x-form.text wire:model.defer="inputs.name.{{ $locale }}" :prefix="$this->locales->count() > 1 ? $locale : null" :label="false"/>
                    @endforeach
                </div>
            </x-form.field>
    
            <x-form.slug wire:model.defer="label.slug" prefix="/"/>
        @endif
    </x-form.group>
</x-form>
