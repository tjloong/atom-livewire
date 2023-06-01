<div 
    x-data="{
        show: false,
    }"
    x-show="show"
    x-transition.opacity
    x-on:order-open.window="show = true"
    x-on:order-close.window="show = false"
    class="fixed inset-0 z-40"
>
    <x-modal.overlay/>

    <div class="absolute top-0 bottom-0 right-0 left-0 p-4 md:left-auto md:p-0 md:w-1/2 lg:w-4/12">
        <div class="bg-white rounded-lg shadow-lg border flex flex-col w-full h-full overflow-hidden md:rounded-none">
            <div class="shrink-0 flex items-center justify-between gap-3 p-4 border-b">
                <div class="text-lg font-bold">{{ __('Order Summary') }}</div>
                <x-close wire:click="clear"/>
            </div>

            <div class="grow overflow-auto">
                @if ($errors->has('subscription'))
                    <div class="p-4">
                        <x-alert title="Unable to subscribe plan" type="error">
                            {{ __($errors->first('subscription')) }}
                        </x-alert>
                    </div>
                @elseif ($subscription)
                    <x-form.group>
                        <x-form.field label="To Be Subscribed">
                            <div class="flex flex-col">
                                <div class="flex gap-2">
                                    <div class="grow font-medium truncate">
                                        {{ $subscription->price->description }}
                                    </div>
        
                                    <div class="shrink-0">
                                        {{ currency($subscription->amount, $subscription->currency) }}
                                    </div>
                                </div>
        
                                @if ($subscription->is_trial)
                                    <div class="flex gap-2">
                                        <div class="grow truncate">
                                            {{ __('Trial Discount') }}
                                        </div>
                                        <div class="shrink-0 text-gray-500">
                                            ({{ currency($subscription->discounted_amount) }})
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </x-form.field>
                    </x-form.group>

                    @if ($prorated = data_get($subscription->data, 'prorated'))
                        <x-form.group>
                            <x-form.field label="To Be Terminated">
                                @foreach ($prorated as $item)
                                    <div class="flex flex-col">
                                        <div class="font-medium">{{ data_get($item, 'plan') }} {{ __('Plan') }}</div>
                                        @foreach (array_filter([
                                            'Prorated deduction' => ($discount = data_get($item, 'discount'))
                                                ? '('.currency($discount).')'
                                                : 0,
                                            'Remaining credits' => ($credits = data_get($item, 'credits') - data_get($item, 'discount')) && $credits > 0
                                                ? currency($credits) 
                                                : 0,
                                            'Extension entitled' => data_get($item, 'extension'),
                                        ]) as $label => $val)
                                            <div class="text-gray-500 flex items-center justify-between gap-2">
                                                <div>{{ __($label) }}</div><div>{{ $val }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </x-form.field>
                        </x-form.group>
                    @endif

                    <x-form.group>
                        <x-form.field label="Plan Contract">
                            <div class="flex flex-col">
                                @foreach (array_filter([
                                    'Start date' => format_date($subscription->start_at),
                                    'End date' => $subscription->end_at 
                                        ? format_date($subscription->end_at) 
                                        : __('No end date'),
                                    'Extension entitled' => $subscription->end_at && $subscription->extension
                                        ? __(':count '.str()->plural('day', $subscription->extension), ['count' => $subscription->extension])
                                        : null,
                                    'Actual End Date' => $subscription->end_at && $subscription->extension
                                        ? format_date($subscription->end_at->addDays($subscription->extension))
                                        : null,
                                ]) as $label => $val)
                                    <div class="flex items-center justify-between gap-2">
                                        <div>{{ __($label) }}</div>
                                        <div class="text-gray-500">{{ $val }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </x-form.field>
                    </x-form.group>

                    <x-form.group>
                        <div class="flex items-center justify-between gap-4">
                            <div class="text-lg font-bold">{{ __('Total') }}</div>
                            <div class="text-lg font-bold">
                                {{ currency($subscription->grand_total, $subscription->currency) }}
                            </div>
                        </div>
                    </x-form.group>

                    <div class="bg-slate-100 rounded-lg p-4 flex flex-col gap-4 mx-4">
                        <div>
                            @if ($price->is_recurring && !$subscription->is_trial && !$subscription->start_at->isFuture())
                                <x-form.checkbox wire:model="inputs.enable_auto_billing" label="Enable Auto Billing"/>
                            @endif

                            <x-form.checkbox.privacy wire:model="inputs.agree_privacy"/>
                        </div>

                        <x-button wire:click="submit" label="Continue" icon="arrow-right" color="green" block/>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>