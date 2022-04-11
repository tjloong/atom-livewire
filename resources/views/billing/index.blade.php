<div class="grid gap-6">
    <x-box>
        <x-slot:header>{{ __('Current Subscription Plans') }}</x-slot:header>

        <div class="grid divide-y">
            @foreach ($this->subscriptions as $subscription)
                <div class="p-4 flex flex-wrap justify-between gap-4">
                    <div class="grid">
                        <div class="text-lg font-bold flex items-center gap-2">
                            {{ $subscription->planPrice->plan->name }}
                            @if ($subscription->is_trial)
                                <x-badge color="yellow">{{ __('trial') }}</x-badge>
                            @endif
                        </div>
    
                        @if ($subscription->expired_at)
                            <div class="text-gray-400 font-medium">
                                {{ __('This plan will be expired on :date', ['date' => format_date($subscription->expired_at)]) }}
                            </div>
                        @endif
                    </div>

                    <div class="grid gap-2 text-right">
                        <div>
                            <x-badge class="text-base">{{ $subscription->status }}</x-badge>
                        </div>
                        <div>
                            @if ($href = route('billing.plans', ['plan' => $subscription->planPrice->plan->slug]))
                                @if ($subscription->expired_at)
                                    <a href="{{ $href }}" class="flex items-center gap-1">
                                        {{ __('Renew') }} <x-icon name="right-arrow-alt"/>
                                    </a>
                                @elseif (
                                    $subscription->planPrice->plan->upgradables->count()
                                    || $subscription->planPrice->plan->downgradables->count()
                                )
                                    <a href="{{ $href }}" class="flex items-center gap-1">
                                        {{ __('Change Plan') }} <x-icon name="right-arrow-alt"/>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-box>
</div>