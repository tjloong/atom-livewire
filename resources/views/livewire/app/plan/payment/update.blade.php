<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="'Payment #'.$payment->number" back>
        <x-dropdown>
            <x-slot:anchor>
                <x-button color="gray" label="Download"/>
            </x-slot:anchor>

                @foreach (['invoice', 'receipt'] as $val)
                    <x-dropdown.item 
                        :label="str($val)->headline()"
                        wire:click="download('{{ $val }}')"
                    />
                @endforeach
        </x-dropdown>
    </x-page-header>

    <div class="flex flex-col gap-6">
        <x-box header="Payment Information">
            <div class="flex flex-col divide-y">
                <x-field label="Receipt Number" :value="$payment->number"/>
                <x-field label="Amount" :value="currency($payment->amount, $payment->currency)"/>
                <x-field label="Status" :badge="$payment->status"/>
            </div>
        </x-box>

        @if ($subscription = $payment->subscription)
            <x-box header="Order Summary">
                <div class="flex flex-col divide-y">
                    <x-field label="Plan" :value="$subscription->price->plan->name"/>
                    <x-field label="Price" :value="currency($subscription->amount, $subscription->currency)"/>
                    @if ($subscription->discounted_amount) 
                        <x-field label="Discount" :value="currency($subscription->discounted_amount, $subscription->currency)"/>
                    @endif
                </div>
            </x-box>
        @endif

        @if ($payment->amount > 0)
            <x-box header="Payment Details">
                <div class="flex flex-col divide-y">
                    <x-field label="Mode" :value="str()->headline($payment->mode)"/>

                    @if ($payment->is_auto_billing)
                        <x-field label="Auto Billing" value="Yes"/>
                    @endif

                    @tier('root')
                        @if ($payment->mode === 'stripe')
                            @if ($customerId = data_get($payment->data, 'metadata.stripe_customer_id'))
                                <x-field label="Stripe Customer ID" :value="$customerId"/>
                            @endif
                            @if ($subscriptionId = data_get($payment->data, 'metadata.stripe_subscription_id'))
                                <x-field label="Stripe Customer ID" :value="$subscriptionId"/>
                            @endif
                        @endif

                        @if ($res = data_get($payment->data, 'pay_response'))
                            <div x-data="{ show: false }">
                                <x-field label="Payment Response">
                                    <a x-on:click="show = !show" class="flex items-center justify-end gap-2">
                                        <x-icon name="eye"/> {{ __('View') }}
                                    </a>
                                </x-field>

                                <div x-show="show" class="bg-gray-100 p-4 overflow-auto">
                                    <pre x-text="JSON.stringify(@js($res), null, 4)"></pre>
                                </div>
                            </div>
                        @endif
                    @endtier
                </div>
            </x-box>
        @endif
    </div>
</div>