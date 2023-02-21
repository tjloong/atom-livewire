<x-form :header="current_route('app.contact.view') ? str()->title($contact->category).' Information' : null">
    <div class="-m-6 flex flex-col divide-y">
        <div class="p-6 grid gap-6 md:grid-cols-2">
            @if ($contact->exists)
                <x-form.field label="Contact Type">{{ str($contact->type)->title() }}</x-form.field>
            @else
                <x-form.select label="Contact Type"
                    wire:model="contact.type"
                    :options="[
                        ['value' => 'person', 'label' => 'Individual'],
                        ['value' => 'company', 'label' => 'Company'],
                    ]"
                    :error="$errors->first('contact.type')"
                    required
                />
            @endif

            <x-form.text
                :label="$contact->category.' Name'"
                wire:model.defer="contact.name"
                :error="$errors->first('contact.name')"
                required
            />

            @if ($contact->exists)
                <x-form.select.owner
                    wire:model="contact.owned_by"
                />
            @endif

            <x-form.field :label="['person' => 'Avatar', 'company' => 'Logo'][$contact->type]">
                @if ($contact->avatar_id) <x-thumbnail :file="$contact->avatar_id" wire:remove="$set('contact.avatar_id', null)"/>
                @else <x-form.file wire:model="contact.avatar_id" accept="image/*"/>
                @endif
            </x-form.field>
        </div>

        <div class="p-6 grid gap-6 md:grid-cols-2">
            <x-form.email
                label="Email"
                wire:model.defer="contact.email"
            />
    
            <x-form.text
                label="Phone"
                wire:model.defer="contact.phone"
            />

            @if ($contact->type === 'company')
                <x-form.text
                    label="Fax"
                    wire:model.defer="contact.fax"
                />

                <x-form.text
                    label="Business Registration Number"
                    wire:model.defer="contact.brn"
                />
        
                <x-form.text
                    label="Tax Number"
                    wire:model.defer="contact.tax_number"
                />

                <x-form.text
                    label="Website"
                    wire:model.defer="contact.website"
                />
            @endif
        </div>

        <div class="p-6 grid gap-6 md:grid-cols-2">
            <x-form.text label="Address Line 1"
                wire:model.defer="contact.address_1"
            />

            <x-form.text label="Address Line 2"
                wire:model.defer="contact.address_2"
            />

            <x-form.text label="City"
                wire:model.defer="contact.city"
            />

            <x-form.text label="Postcode"
                wire:model.defer="contact.zip"
            />

            <x-form.select.country
                wire:model="contact.country"
            />

            @if ($contact->country)
                <x-form.select.state :country="$contact->country"
                    wire:model="contact.state"
                    :uid="uniqid()"
                />
            @endif
        </div>

        @if (count($fields))
            <div class="p-6 flex flex-col gap-4">
                <div class="text-lg font-medium">
                    {{ __('Additional Information') }}
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($fields as $i => $field)
                        @if (in_array(data_get($field, 'type'), ['boolean', 'dropdown', 'multiple']))
                            <x-form.select
                                wire:model="fields.{{ $i }}.value"
                                :label="data_get($field, 'label')"               
                                :options="data_get($field, 'type') === 'boolean' ? ['Yes', 'No'] : data_get($field, 'options')"
                                :multiple="data_get($field, 'type') === 'multiple'"
                                :required="data_get($field, 'required', false)"
                                :error="$errors->first('fields.'.$i.'.value')"
                            />
                        @elseif (data_get($field, 'type') === 'date')
                            <x-form.date
                                wire:model="fields.{{ $i }}.value"
                                :label="data_get($field, 'label')"
                                :required="data_get($field, 'required', false)"
                                :error="$errors->first('fields.'.$i.'.value')"
                            />
                        @else
                            <x-dynamic-component :component="'form.'.data_get($field, 'type')"
                                wire:model.defer="fields.{{ $i }}.value"
                                :label="data_get($field, 'label')"
                                :required="data_get($field, 'required', false)"
                                :error="$errors->first('fields.'.$i.'.value')"
                            />
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-box>
