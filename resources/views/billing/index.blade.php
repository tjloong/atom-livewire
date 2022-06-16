<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Billing Management"/>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-4">
            <x-box header="Current Subscription Plans">
                <div class="grid divide-y">
                    @foreach ($this->plans as $plan)
                        <div class="p-4 grid gap-2">
                            <div class="grid">
                                <div class="text-lg font-bold flex items-center gap-2">
                                    {{ $plan->name }}
                                    @if ($plan->is_trial)
                                        <x-badge color="yellow">{{ __('trial') }}</x-badge>
                                    @endif
                                </div>
            
                                @if ($plan->expired_at)
                                    <div class="text-gray-400 font-medium">
                                        {{ __('Expiring on :date', ['date' => format_date($plan->expired_at)]) }}
                                    </div>
                                @endif
                            </div>
        
                            @if ($btn = collect([
                                ['label' => __('Renew'), 'enabled' => !empty($plan->expired_at)],
                                ['label' => __('Change Plan'), 'enabled' => $plan->upgradables->count() || $plan->downgradables->count()],
                            ])->where('enabled', true)->first())
                                <div>
                                    <a href="{{ route('billing.plans', ['plan' => $plan->slug]) }}">
                                        {{ data_get($btn, 'label') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-box>
        </div>
    
        <div class="md:col-span-8">
            @livewire('atom.app.account-payment.listing', compact('account'))
        </div>
    </div>
</div>