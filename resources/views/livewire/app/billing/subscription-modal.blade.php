<x-modal uid="subscription-modal" header="Subscription">
    @if ($queue)
        <div class="p-6 grid gap-4">
            <x-alert title="Cancelling Auto Renewal" type="warning">
                {{ __('The following subscriptions are within the same auto billing cycle. All of them will be cancelled. This operation is irreversible.') }}
            </x-alert>

            <div class="flex flex-col gap-2">
                @foreach ($queue as $item)
                    <x-box>
                        <div class="p-4 flex items-center justify-between gap-2">
                            <div class="font-medium">
                                {{ $item->name }} <x-badge :label="$item->status" :color="$item->status_color"/>
                            </div>
    
                            <div class="shrink-0 text-sm text-gray-500">
                                {{ collect([format_date($item->start_at), format_date($item->end_at)])->filter()->join(' ~ ') }}
                            </div>
                        </div>
                    </x-box>
                @endforeach
            </div>
        </div>

        <x-slot:foot>
            <div class="flex items-center gap-2">
                <x-button.confirm icon="circle-exclamation" color="red" label="Cancel Auto Renewal" 
                    title="Cancel Auto Renewal"
                    message="Are you sure to cancel the auto renewal? This action is irreversible."
                    callback="cancel"
                    :params="true"
                />

                <x-button label="Do Not Cancel" wire:click="cancel(false)"/>
            </div>
        </x-slot:foot>
    @elseif ($subscription)
        <div class="p-4 flex flex-col gap-4">
            <div class="flex flex-col gap-4">
                <x-form.field label="Plan">
                    <div class="text-lg font-medium">{{ $subscription->name }}</div>
                    <div class="text-gray-500">{{ $subscription->description }}</div>
                </x-form.field>

                @if ($price = $subscription->price)
                    <x-form.field label="Price">
                        <div class="text-lg font-medium">
                            @if ($amount = data_get($subscription->data, 'amount')) {{ currency($amount, data_get($subscription->data, 'currency')) }}
                            @else {{ __('Free') }}
                            @endif
                        </div>
                    </x-form.field>
                @endif

                @if ($features = $subscription->price->plan->features)
                    <x-form.field label="Features">
                        @foreach ($features as $feat)
                            <div class="flex items-center gap-2">
                                @if (str($feat)->startsWith('x ')) <x-icon name="circle-xmark" class="text-gray-400"/>
                                @else <x-icon name="circle-check" class="text-green-500"/>
                                @endif

                                {{ str($feat)->replaceFirst('x ', '') }}
                            </div>
                        @endforeach
                    </x-form.field>
                @endif
            </div>

            <div class="bg-white border rounded-lg">
                <div class="flex flex-col divide-y">
                    <x-field label="Start Date" :value="format_date($subscription->start_at)"/>
                    <x-field label="End Date" :value="format_date($subscription->end_at) ?? '--'"/>

                    @if ($subscription->is_auto_renew)
                        <x-field label="Auto Renew">
                            <div class="text-right">
                                {{ __('Enabled') }}<br>
                                <x-link label="Cancel Auto Renew" wire:click="cancel" class="text-sm"/>
                            </div>
                        </x-field>
                    @else
                        <x-field label="Auto Renew" value="Disabled"/>
                    @endif
                </div>
            </div>
        </div>

        <x-slot:foot>
            <div class="flex items-center gap-2">
                <x-button label="Change Plan" :href="route('app.billing.checkout')" color="theme"/>
                <x-button label="Close" x-on:click="$dispatch('close')"/>
            </div>
        </x-slot:foot>
    @endif
</x-modal>