<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="'Payment #'.$accountPayment->number" back>
        <x-dropdown>
            <x-slot:anchor>
                <x-button inverted icon="download" label="Download"/>
            </x-slot:anchor>

                @foreach (['invoice', 'receipt'] as $val)
                    <x-dropdown.item 
                        :label="str($val)->headline()"
                        wire:click="download('{{ $val }}')"
                    />
                @endforeach
        </x-dropdown>
    </x-page-header>

    <div class="grid gap-6">
        <x-box header="Payment Information">
            <div class="p-5 grid gap-4">
                <x-box.row label="Receipt Number">
                    {{ $accountPayment->number }}
                </x-box.row>
    
                <x-box.row label="Amount">
                    {{ currency($accountPayment->amount, $accountPayment->currency) }}
                </x-box.row>
    
                <x-box.row label="Status">
                    <x-badge>{{ $accountPayment->status }}</x-badge>
                </x-box.row>
            </div>
        </x-box>

        @if ($items = $accountPayment->accountOrder->accountOrderItems)
            <x-box header="Order Summary">
                <div class="grid divide-y">
                    @foreach ($items as $item)
                        <x-box.row :label="$item->name" class="py-2 px-4">
                            <div class="text-right">
                                <div>{{ currency($item->grand_total, $item->currency) }}</div>

                                @if ($item->discount) 
                                    <div class="text-sm text-gray-500">
                                        {{ __('Discounted') }} {{ currency($item->discount, $item->currency) }}
                                    </div>
                                @endif
                            </div>
                        </x-box.row>
                    @endforeach
                </div>
            </x-box>
        @endif

        @if ($accountPayment->amount > 0)
            <x-box header="Payment Details">
                <div class="grid gap-4 p-4">
                    @if ($provider = $accountPayment->provider)
                        <x-box.row label="Provider">
                            {{ str()->headline($provider) }}
                        </x-box.row>

                        @if ($res = data_get($accountPayment->data, 'pay_response'))
                            @if ($accountPayment->is_auto_billing)
                                <x-box.row label="Auto Billing">{{ __('Yes') }}</x-box.row>
                            @endif
        
                            @if ($provider === 'stripe')
                                @if ($customerId = data_get($accountPayment->data, 'metadata.stripe_customer_id'))
                                    <x-box.row label="Stripe Customer ID">{{ $customerId }}</x-box.row>
                                @endif
                                @if ($subscriptionId = data_get($accountPayment->data, 'metadata.stripe_subscription_id'))
                                    <x-box.row label="Stripe Customer ID">{{ $subscriptionId }}</x-box.row>
                                @endif
                            @endif

                            @if (auth()->user()->isAccountType('root'))
                                <x-box.row label="Response Data">
                                    <a x-on:click="show = !show">{{ __('View') }}</a>
                                </x-box.row>
                    
                                <div x-show="show" x-transition class="p-4 w-full overflow-auto bg-gray-100">
                                    <pre x-text="JSON.stringify(@js($res), null, 4)"></pre>
                                </div>
                            @endif
                        @endif
                    @endif
                </div>
            </x-box>
        @endif
    </div>
</div>