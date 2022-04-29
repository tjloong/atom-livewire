<div class="grid gap-6">
    <x-box>
        <x-slot:header>{{ __('Current Subscription Plans') }}</x-slot:header>

        <div class="grid divide-y">
            @foreach ($this->plans as $plan)
                <div class="p-4 flex flex-wrap justify-between gap-4">
                    <div class="grid">
                        <div class="text-lg font-bold flex items-center gap-2">
                            {{ $plan->name }}
                            @if ($plan->is_trial)
                                <x-badge color="yellow">{{ __('trial') }}</x-badge>
                            @endif
                        </div>
    
                        @if ($plan->expired_at)
                            <div class="text-gray-400 font-medium">
                                {{ __('This plan will be expired on :date', ['date' => format_date($plan->expired_at)]) }}
                            </div>
                        @endif
                    </div>

                    @if ($btn = collect([
                        ['label' => __('Renew'), 'enabled' => !empty($plan->expired_at)],
                        ['label' => __('Change Plan'), 'enabled' => $plan->upgradables->count() || $plan->downgradables->count()],
                    ])->where('enabled', true)->first())
                        <div>
                            <x-button href="{{ route('billing.plans', ['plan' => $plan->slug]) }}">
                                {{ data_get($btn, 'label') }}
                            </x-button>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </x-box>
</div>