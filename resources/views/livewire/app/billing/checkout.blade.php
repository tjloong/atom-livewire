<div class="max-w-screen-sm mx-auto">
    <x-page-header title="Order Summary" back/>

    <div class="grid gap-6">
        <div class="grid gap-6">
            <x-box>
                <div class="grid divide-y">
                    @foreach ($accountOrderItems as $i => $accountOrderItem)
                        <div class="p-4 flex flex-wrap justify-between gap-4">
                            <div class="font-medium text-gray-500">
                                {{ data_get($accountOrderItem, 'name') }}
                            </div>

                            <div class="shrink-0 text-right">
                                @if ($trial = data_get($accountOrderItem, 'data.trial'))
                                    {{ currency(0, data_get($accountOrderItem, 'currency')) }}
                                @elseif ($discount = data_get($accountOrderItem, 'discounted_amount'))
                                    <div class="text-gray-500 line-through font-medium">
                                        {{ currency(data_get($accountOrderItem, 'amount'), data_get($accountOrderItem, 'currency')) }}
                                    </div>
                                    <div class="text-sm text-red-500 font-medium">
                                        {{ __('Discounted') }} {{ currency($discount) }}
                                    </div>
                                @else
                                    {{ currency(data_get($accountOrderItem, 'grand_total'), data_get($accountOrderItem, 'currency')) }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <x-slot name="buttons">
                    <div class="grid gap-4">
                        <div class="flex items-center justify-between gap-4">
                            <div class="text-lg font-bold">{{ __('Total') }}</div>
                            <div class="text-lg font-bold">
                                {{ currency(data_get($this->total, 'amount'), data_get($this->total, 'currency')) }}
                            </div>
                        </div>

                        @if (data_get($this->total, 'amount') <= 0)
                            <div class="grid gap-4">
                                <x-form.agree tnc wire:model="accountOrder.data.agree_tnc"/>

                                @if ($errors->any())
                                    <x-alert :errors="$errors->all()"/>
                                @endif
                            </div>
                        @endif
                    </div>
                </x-slot>
            </x-box>
        
            @if (data_get($this->total, 'amount') <= 0)
                <div>
                    <x-button icon="check" label="Checkout" wire:click="submit"/>
                </div>
            @else
                <x-payment-gateway header="Payment Method">
                    <x-slot:stripe>
                        <x-form.checkbox 
                            label="Enable Auto Billing"
                            wire:model="accountOrder.data.enable_auto_billing"
                        />
                    </x-slot:stripe>

                    <div class="grid gap-4">
                        <x-form.agree tnc wire:model="accountOrder.data.agree_tnc"/>
                        @if ($errors->any()) <x-alert :errors="$errors->all()"/> @endif
                    </div>
                </x-payment-gateway>
            @endif
        </div>
    </div>
</div>