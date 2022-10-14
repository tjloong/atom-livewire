<x-box header="Current Subscription Plans">
    <div class="grid divide-y">
        @forelse ($this->accountSubscriptions as $subs)
            <div class="p-4 flex flex-wrap items-center justify-between gap-2 hover:bg-slate-100">
                <div class="font-medium flex items-center gap-2">
                    {{ $subs->planPrice->plan->name }}

                    @if ($subs->is_trial) 
                        <x-badge color="yellow" label="trial"/> 
                    @else
                        <x-badge :label="$subs->status"/>
                    @endif
                </div>

                <div class="md:text-right">
                    @if ($isAutoBilling = !empty(data_get($subs->data, 'stripe_subscription_id')))
                        <div class="flex items-center gap-2">
                            <div class="text-green-500 text-sm flex items-center gap-1">
                                <x-icon name="check" size="12px"/> {{ __('Auto billing enabled') }}
                            </div>
                        </div>
                    @endif

                    <div class="text-gray-400 font-medium text-sm">
                        @if ($subs->status === 'pending')
                            {{ __('Start on :date', ['date' => format_date($subs->start_at)]) }}
                        @elseif ($subs->expired_at)
                            @if ($isAutoBilling)
                                {{ __('Auto renew on :date', ['date' => format_date($subs->expired_at)]) }}
                            @else
                                {{ __('Expiring on :date', ['date' => format_date($subs->expired_at)]) }}
                            @endif
                        @endif
                    </div>

                    @if ($isAutoBilling)
                        <a
                            class="text-sm"
                            wire:click="$emitTo('atom.app.billing.cancel-auto-billing-modal', 'open', {{ $subs->id }})"
                        >
                            {{ __('Cancel') }}
                        </a>
                    @elseif ($subs->status === 'active')
                        <a href="{{ route('app.billing.plans', ['plan' => $subs->planPrice->plan->slug]) }}" class="text-sm">
                            {{ $subs->expired_at ? __('Renew Plan') : __('Change Plan') }}
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <x-empty-state title="No subscription" subtitle="You do not have any active subscription">
                <x-button
                    label="Subscribe a plan"
                    :href="route('app.billing.plans')"
                />
            </x-empty-state>        
        @endforelse
    </div>
</x-box>
