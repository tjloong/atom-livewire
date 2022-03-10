<div class="grid gap-6 md:grid-cols-12">
    <div class="md:col-span-8">
        <div class="grid gap-6">
            <h1 class="text-xl font-bold">Order Summary</h1>
        
            <x-box>
                <div class="p-4">
                    <div class="flex flex-wrap justify-between gap-4">
                        <div>
                            <div class="font-bold">{{ $plan->name }}</div>
                            <div class="flex gap-1 text-sm font-medium">
                                <div>{{ str($price->recurring)->headline() }}</div>
                                @if ($plan->trial)
                                    <div class="text-gray-500">
                                        ({{ $plan->trial }} {{ str('day')->plural($plan->trial) }} Trial)
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0 font-bold">
                            {{ currency($price->amount, $price->currency) }}
                        </div>
                    </div>
                </div>

                <x-slot name="buttons">
                    <div class="flex items-center justify-between gap-4">
                        <div class="text-lg font-bold">Total</div>
                        <div class="text-lg font-bold">{{ currency($total['amount'], $total['currency']) }}</div>
                    </div>
                </x-slot>
            </x-box>
        
            <a href="{{ route('billing') }}" class="text-gray-500 font-medium flex items-center gap-1 text-sm">
                <x-icon name="left-arrow-alt"/> Back to Plans
            </a>
        </div>
    </div>

    <div class="md:col-span-4">
    </div>
</div>
