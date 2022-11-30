<div class="max-w-screen-xl mx-auto">
    <x-page-header back>
        <x-slot:title>
            <div class="flex items-center gap-3">
                <x-avatar 
                    :url="optional($contact->logo)->url" 
                    :placeholder="$contact->name"
                    size="60"
                />
                <div>
                    <div class="text-2xl font-bold">{{ $contact->name }}</div>
                    <div class="text-gray-500">{{ $contact->email }}</div>
                </div>
            </div>
        </x-slot:title>

        <div class="flex items-center gap-2">
            <x-dropdown>
                <x-slot:anchor>
                    <x-button color="gray" 
                        label="Create..." 
                        :icon="['name' => 'chevron-down', 'position' => 'right']"
                    />
                </x-slot:anchor>

                <x-dropdown.item label="Contact Person" 
                    wire:click="$emitTo('{{ lw('app.contact.form.person-modal') }}', 'open')"
                />
            </x-dropdown>
    
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
        <div class="md:w-1/3">
            <x-box class="rounded-lg">
                <div class="grid divide-y">
                    @foreach (collect([
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

        <div class="md:w-2/3">
            <div class="flex flex-col gap-6">
                <x-tab wire:model="tab">
                    <x-tab.item name="person" label="Contact Persons"/>
                </x-tab>

                @livewire(lw('app.contact.view.'.$tab), compact('contact'), key($tab))
            </div>
        </div>
    </div>

    @livewire(lw('app.contact.form.person-modal'), compact('contact'), key('person-modal'))
</div>