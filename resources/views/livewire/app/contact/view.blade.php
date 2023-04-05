<div class="{{ count($this->tabs) ? 'max-w-screen-xl' : 'max-w-screen-md' }} mx-auto">
    <x-page-header back>
        <x-slot:title>
            <div class="flex items-center gap-3">
                <x-thumbnail size="60" circle color="random"
                    :url="optional($contact->avatar)->url" 
                    :placeholder="$contact->name"
                />

                <div>
                    <div class="text-2xl font-bold">{{ $contact->name }}</div>
                    <div class="text-gray-500">{{ $contact->email }}</div>
                </div>
            </div>
        </x-slot:title>

        <div class="flex items-center gap-2">
            @can('contact.update')
                <x-button color="gray"
                    label="Edit"
                    :href="route('app.contact.update', [$contact->id])"
                />
            @endcan
    
            @can('contact.delete')
                <x-button.delete inverted
                    title="Delete Contact"
                    message="Are you sure to delete this contact?"
                />
            @endcan
        </div>
    </x-page-header>
    
    <div class="flex flex-col gap-6 md:flex-row">
        <div class="{{ count($this->tabs) ? 'md:w-1/3' : 'w-full' }}">
            <x-box>
                <div class="grid divide-y">
                    @foreach (collect($this->fields)->filter() as $field)
                        @if (
                            (data_get($field, 'type') === 'items' && ($items = data_get($field, 'value')))
                            || ($items = data_get($field, 'items'))
                        )
                            <div class="py-2 px-4">
                                <x-form.items :label="data_get($field, 'label')">
                                    <div class="grid divide-y">
                                        @foreach ($items as $item)
                                            <div class="text-sm py-2 px-4">
                                                @if (is_string($item)) {!! $item !!}
                                                @elseif ($label = data_get($item, 'label'))
                                                    <div class="flex items-center justify-between gap-2">
                                                        <div class="text-gray-500 font-medium">{{ $label }}</div>
                                                        <div>{!! data_get($item, 'value') !!}</div>
                                                    </div>
                                                @elseif ($icon = data_get($item, 'icon')) 
                                                    <div class="flex gap-2">
                                                        <x-icon :name="$icon" size="12" class="shrink-0 text-gray-400 mt-1"/>
                                                        <div>{!! data_get($item, 'value') !!}</div>
                                                    </div>
                                                @else
                                                    {!! data_get($item, 'value') !!}
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </x-form.items>
                            </div>
                        @else
                            <x-box.row :label="data_get($field, 'label')">
                                {!! data_get($field, 'value') !!}
                            </x-box.row>
                        @endif
                    @endforeach
                </div>
            </x-box>
        </div>

        @if (count($this->tabs))
            <div class="md:w-2/3">
                <div class="flex flex-col gap-6">
                    <x-tab wire:model="tab">
                        @foreach ($this->tabs as $item)
                            @if ($dd = data_get($item, 'dropdown'))
                                <x-tab.dropdown :label="data_get($item, 'label')">
                                    @foreach ($dd as $dditem)
                                        <x-tab.dropdown.item 
                                            :name="data_get($dditem, 'slug')" 
                                            :label="data_get($dditem, 'label')"
                                        />
                                    @endforeach
                                </x-tab.dropdown>
                            @else
                                <x-tab.item 
                                    :name="data_get($item, 'slug')" 
                                    :label="data_get($item, 'label')"
                                    :icon="data_get($item, 'icon')"
                                    :count="data_get($item, 'count')"
                                />
                            @endif
                        @endforeach
                    </x-tab>

                    @if ($lw = $this->livewire)
                        @livewire(
                            lw(data_get($lw, 'name') ?? $lw),
                            array_merge(compact('contact'), data_get($lw, 'data', [])),
                            key($tab)
                        )
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>