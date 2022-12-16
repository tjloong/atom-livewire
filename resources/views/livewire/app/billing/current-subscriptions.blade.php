<x-box header="Current Subscription Plans">
    @if (count($this->subscriptions))
        <div class="bg-gray-100 p-4 grid gap-4 md:grid-cols-2">      
            @foreach ($this->subscriptions as $subscription)
                @php $isAutoBilling = !empty(data_get($subscription->data, 'stripe_subscription_id')) @endphp

                <x-box class="rounded-lg">
                    <div class="p-4">
                        <div class="flex gap-2">
                            <div class="grow font-semibold">
                                {{ $subscription->planPrice->plan->name }}
                            </div>

                            <div class="shrink-0">
                                @if ($subscription->is_trial) <x-badge color="yellow" label="trial"/> 
                                @else <x-badge :label="$subscription->status"/>
                                @endif        
                            </div>
                        </div>

                        <div class="text-gray-400 font-medium text-sm">
                            @if ($subscription->status === 'pending')
                                {{ __('Start on :date', ['date' => format_date($subscription->start_at)]) }}
                            @elseif ($subscription->expired_at)
                                @if ($isAutoBilling)
                                    {{ __('Auto renew on :date', ['date' => format_date($subscription->expired_at)]) }}
                                @else
                                    {{ __('Expiring on :date', ['date' => format_date($subscription->expired_at)]) }}
                                @endif
                            @endif
                        </div>
    
                        @if ($isAutoBilling)
                            <div class="text-green-500 text-sm flex items-center gap-2">
                                <x-icon name="check"/> {{ __('Auto billing enabled') }}
                            </div>
                        @endif

                        @if (auth()->user()->account_id === $account->id)
                            @if ($isAutoBilling)
                                <x-slot:foot class="py-2 px-4">
                                    <a
                                        class="text-sm"
                                        wire:click="openCancelAutoBillingModal({{ $subscription->id }})"
                                    >
                                        {{ __('Cancel Auto Billing') }}
                                    </a>
                                </x-slot:foot>
                            @elseif ($subscription->status === 'active')
                                <x-slot:foot class="py-2 px-4">
                                    <a href="{{ route('app.billing.plan', ['plan' => $subscription->planPrice->plan->slug]) }}" class="text-sm">
                                        {{ $subscription->expired_at ? __('Renew Plan') : __('Change Plan') }}
                                    </a>
                                </x-slot:foot>
                            @endif
                        @endif
                    </div>
                </x-box>
            @endforeach
        </div>
    @else
        <x-empty-state title="No subscription" subtitle="You do not have any active subscription">
            <x-button
                label="Subscribe a plan"
                :href="route('app.billing.plan')"
            />
        </x-empty-state>
    @endif
</x-box>
