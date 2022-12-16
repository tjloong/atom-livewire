<div class="max-w-screen-sm mx-auto">
    <x-page-header title="Order Summary" back/>

    <div class="flex flex-col gap-6">
        <x-box>
            <div class="grid divide-y">
                @foreach ($orderItems as $i => $orderItem)
                    <div class="p-4 flex flex-wrap justify-between gap-4">
                        <div class="font-semibold text-gray-500">
                            {{ data_get($orderItem, 'name') }}
                        </div>

                        <div class="shrink-0 text-right">
                            @if ($trial = data_get($orderItem, 'data.trial'))
                                {{ currency(0, data_get($orderItem, 'currency')) }}
                            @elseif ($discount = data_get($orderItem, 'discounted_amount'))
                                <div class="text-gray-500 line-through font-medium">
                                    {{ currency(data_get($orderItem, 'amount'), data_get($orderItem, 'currency')) }}
                                </div>
                                <div class="text-sm text-red-500 font-medium">
                                    {{ __('Discounted') }} {{ currency($discount) }}
                                </div>
                            @else
                                {{ currency(data_get($orderItem, 'grand_total'), data_get($orderItem, 'currency')) }}
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <x-slot:foot>
                <div class="grid gap-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-lg font-bold">{{ __('Total') }}</div>
                        <div class="text-lg font-bold">
                            {{ currency(data_get($this->total, 'amount'), data_get($this->total, 'currency')) }}
                        </div>
                    </div>

                    @if (data_get($this->total, 'amount') <= 0)
                        <div class="grid gap-4">
                            <x-form.agree tnc wire:model="order.data.agree_tnc"/>

                            @if ($errors->any())
                                <x-alert :errors="$errors->all()"/>
                            @endif
                        </div>
                    @endif
                </div>
            </x-slot:foot>
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
                        wire:model="order.data.enable_auto_billing"
                    />
                </x-slot:stripe>

                <div class="grid gap-4">
                    <x-form.agree tnc wire:model="order.data.agree_tnc"/>
                    @if ($errors->any()) <x-alert :errors="$errors->all()"/> @endif
                </div>
            </x-payment-gateway>
        @endif
    </div>
</div>