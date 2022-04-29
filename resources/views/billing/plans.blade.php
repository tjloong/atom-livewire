<div class="grid gap-6">
    <div class="text-lg font-semibold">
        Select Subscription Plan
    </div>

    <div class="grid gap-6 {{ $this->plans->count() >= 3 ? 'md:grid-cols-3' : 'md:grid-cols-2' }}">
        @foreach ($this->plans as $plan)
            <x-builder.pricing
                :plan="$plan"
                :prices="
                    $plan->planPrices
                        ->map(fn($planPrice) => $planPrice->append('recurring'))
                        ->map(fn($planPrice) => array_merge($planPrice->toArray(), [
                            'is_subscribed' => $this->subscriptions->where('plan_price_id', $planPrice->id)->count() > 0,
                        ]))
                        ->toArray()
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
                                        <div class="font-medium">Currently Subscribed</div>
                                    </div>

                                    @if ($subscription->expired_at)
                                        <x-button :href="route('billing.checkout', ['plan' => $plan->slug, 'price' => $planPrice->id])">
                                            Renew
                                        </x-button>
                                    @endif
                                </div>
                            @else
                                <x-button 
                                    block
                                    size="md" 
                                    :href="route('billing.checkout', ['plan' => $plan->slug, 'price' => $planPrice->id])"
                                >
                                    Subscribe
                                </x-button>
                            @endif
                        </div>
                    @endforeach
                </x-slot:cta>
            </x-builder.pricing>
        @endforeach
    </div>

    @if ($this->subscriptions->count())
        <div>
            <a href="{{ route('billing') }}" class="flex items-center gap-1 text-gray-500">
                <x-icon name="left-arrow-alt"/> Cancel
            </a>
        </div>
    @endif
</div>