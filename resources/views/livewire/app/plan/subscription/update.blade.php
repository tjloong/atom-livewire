<div class="max-w-screen-sm mx-auto">
    <x-page-header 
        :title="$subscription->user->name" 
        :subtitle="collect([$subscription->price->plan->name, $subscription->price->name])->join(', ')"
        back
    />

    <x-form>
        <div class="-m-5 flex flex-col divide-y">
            <x-box.row label="Plan">{{ $subscription->price->plan->name }}</x-box.row>
            <x-box.row label="Price">{{ $subscription->price->name }}</x-box.row>
            
            @if ($this->payment)
                <x-box.row label="Payment">
                    <a href="{{ route('app.plan.payment.update', [$this->payment->id]) }}">
                        {{ $this->payment->number }}
                    </a>
                </x-box.row>
            @endif

            <x-box.row label="Status">
                @if ($subscription->is_trial) <x-badge label="trial" color="yellow"/> @endif
                <x-badge :label="$subscription->status"/>
            </x-box.row>

            <div class="p-4 grid gap-6 md:grid-cols-2">
                <x-form.date label="Start Date"
                    wire:model="subscription.start_at"
                    :error="$errors->first('subscription.start_at')"
                    required
                />

                <x-form.date label="Expiry Date"
                    wire:model="subscription.expired_at"
                />
            </div>
        </div>

        <x-slot:foot>
            <x-button.submit/>
        </x-slot:foot>
    </x-form>
</div>