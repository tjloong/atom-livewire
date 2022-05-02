<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="__('Payment').'#'.$accountPayment->number" back/>

    <div class="grid gap-6">
        <x-box>
            <div class="p-5">
                <x-input.field>
                    <x-slot:label>{{ __('Receipt Number') }}</x-slot:label>
                    {{ $accountPayment->number }}
                </x-input.field>
    
                <x-input.field>
                    <x-slot:label>{{ __('Amount') }}</x-slot:label>
                    {{ currency($accountPayment->amount, $accountPayment->currency) }}
                </x-input.field>
    
                <x-input.field>
                    <x-slot:label>{{ __('Status') }}</x-slot:label>
                    <x-badge>{{ $accountPayment->status }}</x-badge>
                </x-input.field>
            </div>
        </x-box>

        @if ($items = $accountPayment->accountOrder->accountOrderItems)
            <x-box>
                <x-slot:header>{{ __('Order Summary') }}</x-slot:header>

                <div class="grid divide-y">
                    @foreach ($items as $item)
                        <div class="p-4 flex items-center justify-between gap-2">
                            <div class="grid">
                                <div class="truncate">{{ $item->name }}</div>
                            </div>

                            <div class="shrink-0">
                                {{ currency($item->grand_total, $item->currency) }}
                                @if ($item->discount) 
                                    <div class="text-sm text-gray-500">
                                        {{ __('Discounted') }} {{ currency($item->discount, $item->currency) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-box>
        @endif

        @if ($req = data_get($accountPayment->data, 'pay_request'))
            <x-box :header="__('Payment Details')">
                <div x-data="{ show: false }" class="grid divide-y">
                    <x-box row :label="__('Provider')" :value="str()->headline(data_get($req, 'provider', '--'))"/>

                    @if ($res = data_get($accountPayment->data, 'pay_response'))
                        @if ($accountPayment->account_id !== auth()->user()->account_id)
                            <x-box row x-on:click="show = !show" class="cursor-pointer" :label="__('Response Data')">
                                <x-slot:value>
                                    <a>{{ __('View') }}</a>
                                </x-slot:value>
                            </x-box>

                            <div x-show="show" x-transition class="p-4 w-full overflow-auto bg-gray-100">
                                <pre x-text="JSON.stringify(@js($res), null, 4)"></pre>
                            </div>
                        @endif
                    @endif
                </div>
            </x-box>
        @endif
    </div>
</div>