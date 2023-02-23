<x-modal 
    header="Cancel Auto Billing" 
    uid="cancel-auto-billing-modal"
    class="max-w-screen-sm"
>
    @if ($count = count($subscriptions ?? []))
        <div class="grid gap-4">
            <x-alert>
                {{ __('The following subscriptions are within the same auto billing cycle. All of them will be canceled. This operation is irreversible.') }}
            </x-alert>

            <div class="grid gap-2">
                @foreach ($subscriptions as $subs)
                    <div class="p-4 bg-gray-100 rounded-lg flex items-center justify-between gap-2">
                        <div class="font-medium">
                            {{ $subs->planPrice->plan->name }}
                            <x-badge :label="$subs->status"/>
                        </div>

                        <div class="text-sm font-medium text-gray-400 text-right">
                            @if ($subs->status === 'pending')
                                {{ __('Start on :date', ['date' => format_date($subs->start_at)]) }}
                            @elseif ($subs->expired_at)
                                {{ __('Expiring on :date', ['date' => format_date($subs->expired_at)]) }}
                            @endif

                            <div>
                                {{ $this->stripeSubscriptionId }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <x-slot:foot>
            <div class="flex items-center gap-2">
                <x-button icon="circle-exclamation" color="red"
                    label="Cancel Auto Billing"
                    :href="route('__stripe.cancel-subscription', [
                        'subscription_id' => $this->stripeSubscriptionId,
                        'job' => 'PlanSubscriptionCancel',
                    ])"
                />
    
                <x-button color="gray" inverted
                    label="Do Not Cancel"
                    x-on:click="$dispatch('cancel-auto-billing-modal-close')"
                />
            </div>
        </x-slot:foot>
    @endif
</x-modal>
