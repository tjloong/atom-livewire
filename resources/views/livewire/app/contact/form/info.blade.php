<x-box 
    :header="current_route('app.contact.view')
        ? str()->title($contact->type).' Information'
        : null"
>
    <div class="grid divide-y">
        <div class="p-5 grid gap-6">
            <div class="grid gap-6 md:grid-cols-2">
                <x-form.text
                    :label="$contact->type.' Name'"
                    wire:model.defer="contact.name"
                    :error="$errors->first('contact.name')"
                    required
                />
                
                <x-form.email
                    label="Email"
                    wire:model.defer="contact.email"
                />
    
                <x-form.text
                    label="Phone"
                    wire:model.defer="contact.phone"
                />
        
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

                <x-form.select.owner
                    wire:model="contact.owned_by"
                />

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
    
                @if ($contact->country)
                    <x-form.select.state :country="$contact->country"
                        wire:model="contact.state"
                        :uid="uniqid()"
                    />
                @endif
    
                <x-form.select.country
                    wire:model="contact.country"
                />
            </div>
    
            <x-form.field label="Logo">
                @if ($contact->logo_id) <x-thumbnail :file="$contact->logo_id" wire:remove="$set('contact.logo_id', null)"/>
                @else <x-form.file wire:model="contact.logo_id" accept="image/*"/>
                @endif
            </x-form.field>
        </div>

        @if (count($fields))
            <div class="p-5 grid gap-6">
                <div class="font-semibold">{{ __('Additional Information') }}</div>

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
        @endif
    </div>
    
    <x-slot:foot>
        <x-button.submit type="button" wire:click="submit"/>
    </x-slot:foot>
</x-box>
