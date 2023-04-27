<x-modal uid="subscription-modal" header="Subscription">
    @if ($subscription)
        <div class="p-4 flex flex-col gap-4">
            <div class="flex flex-col gap-4">
                <x-form.field label="Plan">
                    <div class="text-lg font-medium">{{ $subscription->name }}</div>
                    <div class="text-gray-500">{{ $subscription->description }}</div>
                </x-form.field>

                <x-form.field label="Price">
                    <div class="text-lg font-medium">
                        @if ($subscription->amount) {{ currency($subscription->amount, $subscription->currency) }}
                        @else {{ __('Free') }}
                        @endif
                    </div>
                </x-form.field>

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
                                <a wire:click="$emitTo(@js(lw('app.billing.cancel-auto-renew-modal')), 'open', @js($subscription->id))" class="text-sm">
                                    {{ __('Cancel auto renew') }}
                                </a>
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
                <x-button label="Change Plan" :href="route('app.billing.checkout')"/>
                <x-button label="Close" color="gray" x-on:click="$dispatch('close')"/>
            </div>
        </x-slot:foot>
    @endif
</x-modal>