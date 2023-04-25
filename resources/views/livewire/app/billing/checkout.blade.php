<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Change Subscription" back/>

    @if ($subscription)
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <x-box header="Order Summary">
                    <x-slot:buttons>
                        <x-button color="gray" size="xs" label="Clear" icon="close" wire:click="clear"/>
                    </x-slot:buttons>
    
                    <div class="py-2 px-4 flex gap-2">
                        <div class="grow">
                            <div class="font-medium">
                                @if (data_get($subscription, 'is_trial')) {{ data_get($subscription, 'name') }}
                                @else {{ data_get($subscription, 'description') }}
                                @endif
                            </div>
    
                            @if ($start = data_get($subscription, 'start_at')) 
                                <div class="text-gray-500 text-sm">
                                    {{ __('Start on :date', ['date' => format_date($start)]) }} 
                                </div>
                            @endif
    
                            <div class="text-gray-500 text-sm">
                                @if ($end = data_get($subscription, 'end_at')) {{ __('End on :date', ['date' => format_date($end)]) }}
                                @else {{ __('No End Date') }}
                                @endif
                            </div>
                        </div>
                        <div class="shrink-0">
                            {{ currency($payment->amount, $payment->currency) }}
                        </div>
                    </div>
    
                    <x-slot:foot>
                        <div class="flex items-center justify-between gap-4">
                            <div class="text-lg font-bold">{{ __('Total') }}</div>
                            <div class="text-lg font-bold">
                                {{ currency($payment->amount, $payment->currency) }}
                            </div>
                        </div>
                    </x-slot:foot>    
                </x-box>
            </div>

            <div>
                <x-payment-gateway header="Payment Method">
                    <x-slot:stripe>
                        @if ($payment->price->is_recurring && !data_get($subscription, 'is_trial'))
                            <x-form.checkbox wire:model="inputs.enable_auto_billing" label="Enable Auto Billing"/>
                        @endif
                    </x-slot:stripe>
    
                    <div class="grid gap-4">
                        <x-form.checkbox.privacy wire:model="inputs.agree_privacy"/>
                    </div>
                </x-payment-gateway>
            </div>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-3">
            @foreach ($this->plans as $plan)
                <x-plan id="{{ $plan->id }}" :plan="$plan">
                    @if ($subscription = model('plan_subscription')
                        ->status(['active', 'future'])
                        ->plan($plan->id)
                        ->where('user_id', user('id'))
                        ->latest('id')
                        ->first()
                    )
                        @if ($subscription->is_auto_renew) 
                            <div class="text-gray-500 text-sm font-medium">
                                {{ __('Auto renew on :date', ['date' => format_date($subscription->end_at)]) }}
                            </div>
                        @elseif ($subscription->end_at)
                            <div class="text-gray-500 text-sm font-medium">
                                {{ __('Ends on :date', ['date' => format_date($subscription->end_at)]) }}
                            </div>
                            <a class="text-sm" x-on:click="$wire.select(price.code)">
                                {{ __('Subscribe again') }}
                            </a>
                        @else 
                            <div class="text-gray-500 text-sm font-medium">
                                {{ __('Subscribed') }}
                            </div>
                        @endif
                    @else
                        <x-button label="Subscribe" x-on:click="$wire.select(price.code)"/>
                    @endif
                </x-plan>
            @endforeach
        </div>
    @endif
</div>