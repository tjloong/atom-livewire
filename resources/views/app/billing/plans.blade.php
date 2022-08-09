<div>
    <x-page-header title="Select Subscription Plan" :back="$this->subscriptions->count() > 0"/>

    <div class="grid gap-6 md:grid-cols-3">
        @foreach ($this->plans as $plan)
            <x-pricing
                :plan="$plan"
                :prices="
                    $plan->planPrices
                        ->map(fn($planPrice) => $planPrice->append('recurring'))
                        ->map(fn($planPrice) => array_merge($planPrice->toArray(), [
                            'is_subscribed' => $this->subscriptions->where('plan_price_id', $planPrice->id)->count() > 0,
                        ]))
                "
                :trial="$this->subscriptions->count() ? false : $plan->trial"
            >
                <x-slot:cta>
                    @foreach ($plan->planPrices as $planPrice)
                        <div x-show="variant === '{{ $planPrice->recurring }}'">
                            @if ($subscription = $this->subscriptions
                                ->where('plan_price_id', $planPrice->id)
                                ->sortBy('created_at')
                                ->last()
                            )
                                <div class="flex items-center justify-between gap-4">
                                    <div class="text-gray-500 flex items-center gap-2">
                                        <x-icon name="check" size="xs"/> 
                                        <div class="font-medium">
                                            {{ __('Currently Subscribed') }}
                                        </div>
                                    </div>

                                    @if ($subscription->expired_at)
                                        <x-button 
                                            label="Renew"
                                            :href="route('app.billing.checkout', ['plan' => $plan->slug, 'price' => $planPrice->id])"
                                        />
                                    @endif
                                </div>
                            @else
                                <x-button 
                                    block
                                    size="md" 
                                    label="Subscribe"
                                    :href="route('app.billing.checkout', ['plan' => $plan->slug, 'price' => $planPrice->id])"
                                />
                            @endif
                        </div>
                    @endforeach
                </x-slot:cta>
            </x-pricing>
        @endforeach
    </div>
</div>