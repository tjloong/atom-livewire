<div class="{{ tier('root') ? 'max-w-screen-xl' : 'max-w-screen-lg' }} mx-auto">
    <x-page-header :title="$this->title"/>

    @tier('root')
        <x-table :data="$this->tableData">
            <x-slot:header>
                <x-table.searchbar :total="$this->paginator->total()"/>
                
                <x-table.toolbar>
                    <div class="flex items-center gap-2">
                        <x-form.select wire:model="filters.status" :label="false"
                            :options="collect(['active', 'pending', 'expired'])->map(fn($val) => [
                                'value' => $val,
                                'label' => ucfirst($val),
                            ])"
                            placeholder="All Status"
                        />

                        <x-form.select wire:model="filters.plan" :label="false"
                            :options="model('plan')->readable()->orderBy('name')->get()->map(fn($plan) => [
                                'value' => $plan->slug,
                                'label' => $plan->name,
                            ])"
                            placeholder="All Plans"
                        />
                    </div>
                </x-table.toolbar>
            </x-slot:header>
        </x-table>

        {!! $this->paginator->links() !!}
    @else
        <div class="flex flex-col gap-6">
            <x-box header="Current Subscription Plans">
                @if ($this->query->count())
                    <div class="bg-gray-100 p-4 grid gap-4 md:grid-cols-3">      
                        @foreach ($this->query->oldest()->get() as $subscription)
                            @php $isAutoBilling = !empty(data_get($subscription->data, 'stripe_subscription_id')) @endphp
            
                            <x-box class="rounded-lg">
                                <div class="p-4">
                                    <div class="flex gap-2">
                                        <div class="grow font-semibold">
                                            {{ $subscription->price->plan->name }}
                                        </div>
            
                                        <div class="shrink-0">
                                            @if ($subscription->is_trial) <x-badge color="yellow" label="trial"/> 
                                            @else <x-badge :label="$subscription->status"/>
                                            @endif        
                                        </div>
                                    </div>
            
                                    <div class="text-gray-400 font-medium text-sm">
                                        @if ($subscription->status === 'pending')
                                            {{ __('Start on :date', ['date' => format_date($subscription->start_at)]) }}
                                        @elseif ($subscription->expired_at)
                                            @if ($isAutoBilling)
                                                {{ __('Auto renew on :date', ['date' => format_date($subscription->expired_at)]) }}
                                            @else
                                                {{ __('Expiring on :date', ['date' => format_date($subscription->expired_at)]) }}
                                            @endif
                                        @endif
                                    </div>
                
                                    @if ($isAutoBilling)
                                        <div class="text-green-500 text-sm flex items-center gap-2">
                                            <x-icon name="check"/> {{ __('Auto billing enabled') }}
                                        </div>
                                    @endif
            
                                    @if (user('id') === $subscription->user_id)
                                        @if ($isAutoBilling)
                                            <x-slot:foot class="py-2 px-4">
                                                <a wire:click="$emitTo(
                                                    @js(lw('app.plan.subscription.cancel-auto-billing-modal')), 
                                                    'open', 
                                                    @js($subscription->id)
                                                )" class="text-sm">
                                                    {{ __('Cancel Auto Billing') }}
                                                </a>
                                            </x-slot:foot>
                                        @elseif ($subscription->status === 'active')
                                            <x-slot:foot class="py-2 px-4">
                                                <a href="{{ route('app.plan.listing', ['renew' => $subscription->id]) }}" class="text-sm">
                                                    {{ $subscription->expired_at ? __('Renew Plan') : __('Change Plan') }}
                                                </a>
                                            </x-slot:foot>
                                        @endif
                                    @endif
                                </div>
                            </x-box>
                        @endforeach
                    </div>
                @else
                    <x-empty-state title="No subscription" subtitle="You do not have any active subscription">
                        <x-button label="Subscribe a plan" :href="route('app.plan.listing')"/>
                    </x-empty-state>
                @endif
            </x-box>
            
            @livewire(lw('app.plan.payment.listing'), key('payment-history'))
            @livewire(lw('app.plan.subscription.cancel-auto-billing-modal'), key('cancel-auto-billing-modal'))
        </div>
    @endtier
</div>