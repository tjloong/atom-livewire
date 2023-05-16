<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$this->title"/>

    @if ($subscription)
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <x-box header="Order Summary">
                    <x-slot:buttons>
                        <x-button color="gray" size="xs" label="Clear" icon="close" wire:click="clear"/>
                    </x-slot:buttons>
    
                    <div class="flex flex-col">
                        <div class="p-4 flex flex-col gap-2">
                            <div class="font-semibold">{{ __('To Be Subscribed') }}</div>

                            <div class="flex flex-col">
                                <div class="flex gap-2">
                                    <div class="grow font-medium">
                                        {{ $subscription->price->description }}
                                    </div>
        
                                    <div class="shrink-0">
                                        {{ currency($subscription->amount, $subscription->currency) }}
                                    </div>
                                </div>
    
                                @if ($subscription->is_trial)
                                    <div class="flex gap-2">
                                        <div class="grow">
                                            {{ __('Trial Discount') }}
                                        </div>
                                        <div class="shrink-0 text-gray-500">
                                            ({{ currency($subscription->discounted_amount) }})
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($prorated = data_get($subscription->data, 'prorated'))
                            <div class="bg-red-50 p-4 flex flex-col gap-2">
                                <div class="font-semibold">{{ __('To Be Terminated') }}</div>
                                @foreach ($prorated as $item)
                                    <div class="flex flex-col">
                                        <div class="font-medium">{{ data_get($item, 'plan') }} {{ __('Plan') }}</div>
                                        @foreach (array_filter([
                                            'Prorated deduction' => ($discount = data_get($item, 'discount'))
                                                ? '('.currency($discount).')'
                                                : 0,
                                            'Remaining credits' => ($credits = data_get($item, 'credits') - data_get($item, 'discount')) && $credits > 0
                                                ? currency($credits) 
                                                : 0,
                                            'Extension entitled' => data_get($item, 'extension'),
                                        ]) as $label => $val)
                                            <div class="text-gray-500 flex items-center justify-between gap-2">
                                                <div>{{ __($label) }}</div><div>{{ $val }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @endif
    
                        <div class="p-4 flex flex-col gap-2">
                            <div class="font-semibold">{{ __('Plan Contract') }}</div>
                            <div class="flex flex-col">
                                @foreach (array_filter([
                                    'Start date' => format_date($subscription->start_at),
                                    'End date' => $subscription->end_at 
                                        ? format_date($subscription->end_at) 
                                        : __('No end date'),
                                    'Extension entitled' => $subscription->end_at && $subscription->extension
                                        ? __(':count '.str()->plural('day', $subscription->extension), ['count' => $subscription->extension])
                                        : null,
                                    'Actual End Date' => $subscription->end_at && $subscription->extension
                                        ? format_date($subscription->end_at->addDays($subscription->extension))
                                        : null,
                                ]) as $label => $val)
                                    <div class="text-gray-500 flex items-center justify-between gap-2">
                                        <div>{{ __($label) }}</div><div>{{ $val }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
    
                    <x-slot:foot>
                        <div class="flex items-center justify-between gap-4">
                            <div class="text-lg font-bold">{{ __('Total') }}</div>
                            <div class="text-lg font-bold">
                                {{ currency($subscription->grand_total, $subscription->currency) }}
                            </div>
                        </div>
                    </x-slot:foot>    
                </x-box>
            </div>

            <div>
                <x-payment-gateway header="Payment Method">
                    <x-slot:stripe>
                        @if ($price->is_recurring && !$subscription->is_trial)
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
                    @if ($subscription = $plan->subscription)
                        @if ($subscription->is_auto_renew)
                            <x-slot:foot>
                                <div class="text-gray-500 text-sm font-medium">
                                    {{ __('Auto renew on :date', ['date' => format_date($plan->subscription->end_at)]) }}
                                </div>
                                <a class="text-sm" wire:click="$emitTo(@js(lw('app.billing.cancel-auto-renew-modal')), 'open', @js($plan->subscription->id))">
                                    {{ __('Cancel auto renew') }}
                                </a>
                            </x-slot:foot>
                        @elseif ($subscription->status === 'active' && !$subscription->end_at)
                            <x-slot:foot>
                                <div class="text-gray-500 text-sm font-medium flex items-center gap-2">
                                    <x-icon name="check" size="12"/> {{ __('Currently Subscribed') }}
                                </div>
                            </x-slot:foot>
                        @else
                            @if ($subscription->status === 'future')
                                <div class="bg-yellow-100 text-yellow-600 py-2 px-4 rounded-lg text-sm">
                                    <div class="font-bold">{{ __('Upcoming Subscription') }}</div>
                                    {{ collect([format_date($subscription->start_at), format_date($subscription->end_at) ?? 'forever'])->join(' ~ ') }}
                                </div>
                            @else
                                <div class="bg-blue-100 rounded-lg text-blue-600 py-2 px-4 text-sm">
                                    <div class="font-bold">{{ __('Currently Subscribed') }}</div>
                                    {{ collect([format_date($subscription->start_at), format_date($subscription->end_at) ?? 'forever'])->filter()->join(' ~ ') }}
                                </div>
                            @endif
                            <x-slot:foot>
                                <x-button label="Renew" x-on:click="$wire.select(price.code)" color="blue" block/>
                            </x-slot:foot>
                        @endif
                    @else
                        <x-slot:foot>
                            <x-button label="Subscribe" x-on:click="$wire.select(price.code)" block/>
                        </x-slot:foot>
                    @endif
                </x-plan>
            @endforeach
        </div>
    @endif
</div>