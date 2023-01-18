<div class="max-w-screen-md mx-auto">
    <x-page-header :title="'Split Invoice #'.$document->number" back/>

    <x-box>
        <div class="grid divide-y">
            @foreach ($splits as $i => $split)
                <div class="p-4 grid gap-4">
                    <div class="flex items-center justify-between gap-3">
                        @if ($number = data_get($split, 'document_number'))
                            <div class="font-semibold">
                                #{{ $number }}
                            </div>
    
                            <x-badge :label="data_get($split, 'document_status', 'new')"/>
                        @else
                            <div class="font-medium flex items-center gap-2">
                                <x-icon name="circle-info" size="12" class="text-gray-400"/>
                                <div class="text-sm text-gray-500">{{ __('New Split') }}</div>
                            </div>
                        @endif
                    </div>
    
    
                    <div class="grid gap-6 md:grid-cols-3">
                        <x-form.number label="Percentage"
                            unit="%"
                            wire:model.debounce.400ms="splits.{{ $i }}.percentage"
                            min="0"
                            required
                            :readonly="data_get($split, 'disabled')"
                        />
    
                        <x-form.date label="Issue Date"
                            wire:model="splits.{{ $i }}.issued_at"
                        />
    
                        <div class="flex items-center gap-4">
                            <div class="grow">
                                <x-form.field label="Amount">
                                    <div class="form-input">
                                        {{ currency(data_get($split, 'amount', 0)) }}
                                    </div>
                                </x-form.field>
                            </div>
    
                            @if ($i > 0 && !data_get($split, 'disabled'))
                                <div class="shrink-0">
                                    <a wire:click="remove(@js($i))" class="flex">
                                        <x-icon name="circle-minus" class="m-auto text-red-500"/>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
    
                    @if (data_get($split, 'disabled'))
                        <div class="text-sm text-blue-500 font-medium flex items-center gap-2">
                            <x-icon name="circle-info" size="12"/>
                            {{ __('You cannot change the percentage because the status is '.data_get($split, 'document_status')) }}
                        </div>
                    @endif
                </div>
            @endforeach
    
            @if ($errors->any())
                <div class="p-4">
                    <x-alert :errors="$errors->all()"/>
                </div>
            @endif
    
            @if ($splits->sum('percentage') >= 100)
                <div class="py-2 px-4 flex items-center gap-2 justify-center text-green-500">
                    <x-icon name="check"/> {{ __('Invoice is fully splitted.') }}
                </div>
            @else
                <a 
                    wire:click="add"
                    class="py-2 px-4 flex items-center gap-2 justify-center hover:bg-slate-100"
                >
                    <x-icon name="add"/> {{ __('Add Split') }}
                </a>
            @endif
        </div>

        <x-slot:foot>
            <x-button.submit type="button"
                wire:click="submit"
            />
        </x-slot:foot>
    </x-box>
</div>