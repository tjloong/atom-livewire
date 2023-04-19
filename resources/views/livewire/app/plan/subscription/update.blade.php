<div class="max-w-screen-sm mx-auto">
    <x-page-header 
        :title="$subscription->user->name" 
        :subtitle="collect([$subscription->price->plan->name, $subscription->price->name])->join(', ')"
        back
    >
        @tier('root')
            <x-button.confirm label="Mask Login" icon="mask" color="gray"
                title="Mask Login"
                message="You will be login using the subscriber identity. Are you sure to proceed?"
                callback="mask"
            />
        @endtier
    </x-page-header>

    <x-form>
        <div class="flex flex-col divide-y">
            <x-field label="Plan" :value="$subscription->price->plan->name"/>
            <x-field label="Price" :value="$subscription->price->name"/>
            
            @if ($this->payment)
                <x-field label="Payment" 
                    :value="$this->payment->number" 
                    :href="route('app.plan.payment.update', [$this->payment->id])"
                />
            @endif

            <x-field label="Status" :badge="$subscription->is_trial
                ? ['yellow' => 'trial']
                : $subscription->status"
            />
        </div>

        <x-form.group cols="2">
            <x-form.date wire:model="subscription.start_at" label="Start Date"/>
            <x-form.date wire:model="subscription.expired_at" label="Expiry Date"/>
        </x-form.group>
    </x-form>
</div>