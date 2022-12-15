<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Select Subscription Plan" :back="$this->subscriptions->count() > 0"/>

    <div class="grid gap-6 md:grid-cols-3">
        @foreach ($this->plans as $plan)
            <x-pricing
                :plan="$plan"
                :prices="
                    $plan->prices
                        ->map(fn($price) => $price->append('recurring'))
                        ->map(fn($price) => array_merge($price->toArray(), [
                            'is_subscribed' => $this->subscriptions->where('plan_price_id', $price->id)->count() > 0,
                        ]))
                "
                :trial="$this->subscriptions->count() ? false : $plan->trial"
            >
                <x-slot:cta>
                    @foreach ($plan->prices as $price)
                        <div x-show="variant === '{{ $price->recurring }}'">
                            <div class="flex items-center gap-2">
                                @if ($subscription = $this->subscriptions
                                    ->where('plan_price_id', $price->id)
                                    ->sortBy('created_at')
                                    ->last()
                                )
                                    <div class="grow text-gray-500 flex items-center gap-2 font-medium">
                                        <x-icon name="check" class="shrink-0"/> 
                                        <div class="font-medium">
                                            {{ __('Currently Subscribed') }}
                                        </div>
                                    </div>
                                @endif

                                @if (($subscription && $subscription->expired_at) || !$subscription)
                                    <x-button
                                        :size="$subscription ? 'sm' : 'md'"
                                        :label="$subscription ? 'Renew' : 'Subscribe'"
                                        :href="route('app.billing.checkout', ['plan' => $plan->slug, 'price' => $price->id])"
                                        :block="empty($subscription)"
                                    />
                                @endif
                            </div>
                        </div>
                    @endforeach
                </x-slot:cta>
            </x-pricing>
        @endforeach
    </div>
</div>