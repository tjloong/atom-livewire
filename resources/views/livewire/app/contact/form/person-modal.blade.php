<x-modal form
    uid="person-form-modal"
    :header="data_get($input, 'id') ? 'Update Person' : 'Create Person'"
>
    @if ($input)
        <div class="grid gap-6">
            <x-form.text
                label="Person Name"
                wire:model.defer="input.name"
                :error="$errors->first('input.name')"
                required
            />

            @if (
                $salutations = model('label')
                    ->when(
                        model('label')->enabledBelongsToAccountTrait,
                        fn($q) => $q->belongsToAccount(),
                    )
                    ->where('type', 'salutation')
                    ->get()
                    ->toArray()
            )
                <x-form.select
                    label="Salutation"
                    wire:model.defer="input.salutation"
                    :options="$salutations"
                />
            @endif

            <x-form.text
                label="Email"
                wire:model.defer="input.email"
            />

            <x-form.text
                label="Phone"
                wire:model.defer="input.phone"
            />

            <x-form.text
                label="Designation"
                wire:model.defer="input.designation"
            />
        </div>
        
        <x-slot:foot>
            <div class="flex items-center gap-2">
                <x-button.submit/>
    
                @if (data_get($input, 'id'))
                    <x-button.delete inverted
                        title="Delete Person"
                        message="Are you sure to delete this person?"
                    />
                @endif
            </div>
        </x-slot:foot>
    @endif
</x-modal>
