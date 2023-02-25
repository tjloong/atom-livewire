<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Plans">
        @tier('root')
            <x-button label="New Plan" :href="route('app.plan.create')"/>
        @endtier
    </x-page-header>

    @tier('root')
        <x-table :data="$this->paginator->items()">
            <x-slot:header>
                <x-table.searchbar :total="$this->paginator->total()"/>
            </x-slot:header>
        </x-table>

        {!! $this->paginator->links() !!}
    @else
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($this->query->orderBy('id')->get() as $plan)
                <x-pricing
                    :plan="$plan"
                    :prices="
                        $plan->prices
                            ->map(fn($price) => $price->append('recurring'))
                            ->map(fn($price) => array_merge($price->toArray(), [
                                'is_subscribed' => user()->subscriptions->where('plan_price_id', $price->id)->count() > 0,
                            ]))
                    "
                    :trial="user()->subscriptions->count() ? false : $plan->trial"
                >
                    <x-slot:cta>
                        @foreach ($plan->prices as $price)
                            <div x-show="variant === '{{ $price->recurring }}'">
                                <div class="flex items-center gap-2">
                                    @if ($subscription = user()->subscriptions
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
                                            :href="route('app.plan.subscription.create', ['plan' => $plan->slug, 'price' => $price->id])"
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
    @endtier
</div>