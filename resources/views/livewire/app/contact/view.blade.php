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
                    @foreach (collect([
                        'Type' => str($contact->type)->title(),
                        'Email' => $contact->email,
                        'Phone' => $contact->phone,
                        'Fax' => $contact->fax,
                        'BRN' => $contact->brn,
                        'Tax Number' => $contact->tax_number,
                        'Website' => $contact->website,
                    ])->filter(fn($val, $key) => !empty($val)) as $label => $value)
                        <x-box.row :label="$label">{{ $value }}</x-box.row>
                    @endforeach

                    <x-box.row label="Address">
                        {{ format_address($contact) }}
                    </x-box.row>

                    @if($fields = data_get($contact->data, 'fields'))
                        @foreach ($fields as $field)
                            <x-box.row :label="data_get($field, 'label')">
                                {{ data_get($field, 'value') }}
                            </x-box.row>
                        @endforeach
                    @endif

                    @if ($owner = $contact->ownedBy)
                        <x-box.row label="Owner">{{ $owner->name }}</x-box.row>
                    @endif
                </div>
            </x-box>
        </div>

        @if (count($this->tabs))
            <div class="md:w-2/3">
                <div class="flex flex-col gap-6">
                    <x-tab wire:model="tab">
                        @foreach ($this->tabs as $item)
                            <x-tab.item 
                                :name="data_get($item, 'slug')" 
                                :label="data_get($item, 'label')"
                                :icon="data_get($item, 'icon')"
                                :count="data_get($item, 'count')"
                            />
                        @endforeach
                    </x-tab>

                    @if ($com = collect($this->tabs)->firstWhere('slug', $tab))
                        @livewire(
                            lw(data_get($com, 'livewire')), 
                            compact('contact'), 
                            key($tab)
                        )
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>