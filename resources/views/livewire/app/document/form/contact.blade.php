<div class="p-5 bg-slate-100">
    @if ($meta = data_get($inputs, 'metadata'))
        <div class="flex flex-col gap-4">
            <x-form.field :label="$this->label">
                <div class="bg-white rounded-lg shadow border py-2 px-4 flex gap-2">
                    <div class="grow flex items-center gap-3">
                        @if ($avatar = data_get($meta, 'avatar')) 
                            <x-thumbnail :file="$avatar" size="20"/> 
                        @endif
        
                        <div class="font-semibold">
                            {{ data_get($inputs, 'name') }}
                        </div>
                    </div>
        
                    <a wire:click="$set('inputs.contact_id', null)" class="shrink-0 flex text-gray-500">
                        <x-icon name="close" class="m-auto"/>
                    </a>
                </div>
            </x-form.field>

            @if ($addresses = data_get($meta, 'addresses'))
                @if ($address = data_get($inputs, 'address'))
                    <x-form.field label="Address">
                        <div class="bg-white rounded-md border border-gray-300 py-2 px-4 flex items-center gap-2">
                            <div class="grow">
                                {!! nl2br($address) !!}
                            </div>

                            <a wire:click="$set('inputs.address', null)" class="shrink-0 text-gray-500 flex">
                                <x-icon name="close" class="m-auto"/>
                            </a>
                        </div>
                    </x-form.field>
                @else
                    <x-form.select label="Address"
                        wire:model="inputs.address"
                        :options="collect($addresses)->map(fn($val) => [
                            'value' => format_address($val),
                            'label' => format_address($val),
                            'small' => data_get($val, 'is_shipping') ? 'shipping' : null,
                        ])"
                    />
                @endif
            @else
                <x-form.textarea label="Address"
                    wire:model.defer="inputs.address"
                />
            @endif

            @if ($persons = data_get($meta, 'persons'))
                <x-form.select label="Attention To"
                    wire:model="inputs.person"
                    :options="$persons"
                    placeholder="Select Person"
                />
            @endif
        </div>
    @else
        <x-form.select
            :label="$this->label"
            wire:model="inputs.contact_id"
            callback="getContacts"
            :error="$errors->first('inputs.contact_id')"
            required
        >
            @if (Route::has('app.contact.create'))
                <x-slot:footlink 
                    label="Create New"
                    :href="route('app.contact.create', [$this->type])"
                ></x-slot:footlink>
            @endif
        </x-form.select>
    @endif
</div>