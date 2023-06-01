<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$this->title"/>

    <div 
        @if ($preselect)
            x-cloak
            x-data
            x-init="$wire.emitTo(
                @js(lw('app.billing.order')),
                'open',
                @js($preselect),
            )"
        @endif
        class="grid gap-4 md:grid-cols-3"
    >
        @foreach ($this->plans as $plan)
            <x-plan id="{{ $plan->id }}" :plan="$plan">
                @if ($subscription = $plan->subscription)
                    @if ($subscription->is_auto_renew)
                        <x-slot:foot>
                            <div class="flex items-center gap-2">
                                <div class="grow text-gray-500 text-sm font-medium">
                                    {{ __('Auto renew on :date', ['date' => format_date($plan->subscription->end_at)]) }}
                                </div>

                                <div class="shrink-0">
                                    <x-link wire:click="$emitTo(
                                        '{{ lw('app.billing.subscription-modal') }}', 
                                        'open', 
                                        {{ $plan->subscription->id }},
                                    )" label="View" icon="eye" class="text-sm"/>
                                </div>
                            </div>
                        </x-slot:foot>
                    @elseif ($subscription->status === 'active' && !$subscription->end_at)
                        <x-slot:foot>
                            <div class="text-gray-500 text-sm font-medium flex items-center gap-2">
                                <x-icon name="check" size="12"/> {{ __('Currently Subscribed') }}
                            </div>
                        </x-slot:foot>
                    @else
                        @if ($subscription->status === 'future')
                            <div class="bg-yellow-100 text-yellow-600 py-2 px-4 rounded-lg text-sm">
                                <div class="font-bold">{{ __('Upcoming Subscription') }}</div>
                                {{ collect([format_date($subscription->start_at), format_date($subscription->end_at) ?? 'forever'])->join(' ~ ') }}
                            </div>
                        @else
                            <div class="bg-blue-100 rounded-lg text-blue-600 py-2 px-4 text-sm">
                                <div class="font-bold">{{ __('Currently Subscribed') }}</div>
                                {{ collect([format_date($subscription->start_at), format_date($subscription->end_at) ?? 'forever'])->filter()->join(' ~ ') }}
                            </div>
                        @endif

                        <x-slot:foot>
                            <x-button label="Renew" x-on:click="$wire.emitTo(
                                '{{ lw('app.billing.order') }}',
                                'open',
                                price.code,
                            )" color="blue" block/>
                        </x-slot:foot>
                    @endif
                @else
                    <x-slot:foot>
                        <x-button label="Subscribe" x-on:click="$wire.emitTo(
                            '{{ lw('app.billing.order') }}',
                            'open',
                            price.code,
                        )" color="theme" block/>
                    </x-slot:foot>
                @endif
            </x-plan>
        @endforeach
    </div>

    @livewire(lw('app.billing.subscription-modal'), key('subscription-modal'))
    @livewire(lw('app.billing.order'), key('order'))
</div>