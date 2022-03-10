<div class="grid gap-6">
    <div class="text-xl font-bold">
        @if ($subscriptions->count())
            Change Subscription Plan
        @else
            Select Subscription Plan
        @endif
    </div>


    <div class="grid gap-6 md:grid-cols-2">
        @foreach ($plans as $plan)
            <x-builder.pricing
                :plan="$plan"
                :prices="
                    $plan->prices->map(fn($price) => array_merge($price->toArray(), [
                        'is_subscribed' => $subscriptions->where('plan_price_id', $price->id)->count(),
                    ]))->toArray()
                "
                :trial="$subscriptions->count() ? false : $plan->trial"
                :cta="[
                    'text' => 'Subscribe',
                    'href' => route('billing.checkout'),
                    'color' => 'green',
                    'icon' => 'check',
                ]"
            />
        @endforeach
    </div>

    @if ($subscriptions->count())
        <div>
            <a x-on:click="$dispatch('cancel-plan-change')" class="flex items-center gap-1 text-gray-500">
                <x-icon name="left-arrow-alt"/> Cancel
            </a>
        </div>
    @endif
</div>