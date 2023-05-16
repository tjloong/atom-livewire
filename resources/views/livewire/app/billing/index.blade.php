<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Subscription">
        @if ($this->subscriptions->count())
            <x-button label="Change Plan" :href="route('app.billing.checkout')"/>
        @endif
    </x-page-header>

    <div class="flex flex-col gap-4">
        @if ($this->subscriptions->count())
            @foreach ($this->subscriptions as $subscription)
                <x-box>
                    <div 
                        wire:click="$emitTo(@js(lw('app.billing.subscription-modal')), 'open', @js($subscription->id))" 
                        class="p-4 cursor-pointer flex flex-col gap-2 hover:bg-slate-100"
                    >
                        <div class="flex gap-2">
                            <div class="grow">
                                <div class="text-lg font-medium">
                                    {{ $subscription->name }}
                                </div>
                                <div class="text-gray-500">
                                    {{ $subscription->price->plan->description }}
                                </div>
                            </div>

                            @if ($subscription->is_trial || !$subscription->amount)
                                <div class="shrink-0 text-lg font-medium">{{ __('Free') }}</div>
                            @else
                                <div class="shrink-0 text-lg font-medium">
                                    {{ currency($subscription->amount, $subscription->currency) }}
                                </div>
                            @endif
                        </div>

                        <div class="text-gray-500 text-sm font-medium flex items-center gap-2">
                            {{ collect([format_date($subscription->start_at), format_date($subscription->end_at) ?? 'forever'])->join(' ~ ') }}
                            @if ($subscription->is_auto_renew) <br>{{ __('Auto renew on :date', ['date' => format_date($subscription->end_at)]) }} @endif
                        </div>
                    </div>
                </x-box>
            @endforeach
        @else
            <x-box>
                <x-empty-state title="No subscription" subtitle="You do not have any active subscription">
                    <x-button label="Subscribe Plan" :href="route('app.billing.checkout')"/>
                </x-empty-state>
            </x-box>
        @endif
        
        @livewire(lw('app.billing.receipt'), key('receipt'))
    </div>

    @livewire(lw('app.billing.subscription-modal'), key('subscription-modal'))
    @livewire(lw('app.billing.cancel-auto-renew-modal'), key('cancel-auto-renew-modal'))
</div>
