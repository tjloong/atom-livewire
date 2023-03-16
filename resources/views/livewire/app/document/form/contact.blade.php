<div class="bg-slate-100">
    <x-form.group>
        @if ($document->convertedFrom)
            <x-form.field :label="$this->label" :value="data_get($inputs, 'name')"/>
            <x-form.field label="Address" :value="data_get($inputs, 'address')"/>
            @if ($person = data_get($inputs, 'person')) <x-form.field label="Attention To" :value="$person"/> @endif
        @elseif ($meta = data_get($inputs, 'metadata'))
            <x-form.field :label="$this->label">
                <div class="form-input flex gap-2">
                    <div class="grow flex items-center gap-3">
                        @if ($avatar = data_get($meta, 'avatar')) <x-thumbnail :file="$avatar" size="20"/> @endif
                        <div class="font-semibold">{{ data_get($inputs, 'name') }}</div>
                    </div>

                    <div class="shrink-0">
                        <x-close color="red" wire:click="$set('inputs.contact_id', null)"/>
                    </div>
                </div>
            </x-form.field>

            @if ($addresses = data_get($meta, 'addresses'))
                @if ($address = data_get($inputs, 'address'))
                    <x-form.field label="Address">
                        <div class="form-input flex items-center gap-2">
                            <div class="grow">{!! nl2br($address) !!}</div>
                            <div class="shrink-0">
                                <x-close color="red" wire:click="$set('inputs.address', null)"/>
                            </div>
                        </div>
                    </x-form.field>
                @else
                    <x-form.select wire:model="inputs.address" :options="collect($addresses)->map(fn($val) => [
                        'value' => format_address($val),
                        'label' => format_address($val),
                        'small' => data_get($val, 'is_shipping') ? 'shipping' : null,
                    ])"/>
                @endif
            @else
                <x-form.textarea wire:model.defer="inputs.address"/>
            @endif

            @if ($persons = data_get($meta, 'persons'))
                <x-form.select wire:model="inputs.person" label="Attention To" :options="$persons"/>
            @endif
        @else
            <x-form.select wire:model="inputs.contact_id" :label="$this->label" callback="getContacts">
                @if (Route::has('app.contact.create'))
                    <x-slot:footlink 
                        label="Create New"
                        :href="route('app.contact.create', [$this->type])"
                    ></x-slot:footlink>
                @endif
            </x-form.select>
        @endif
    </x-form.group>
</div>