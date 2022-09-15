<x-table :total="$plan->planPrices->count()">
    <x-slot:header>
        <div class="flex items-center justify-between gap-2">
            {{ __('Plan Prices') }}

            <x-button size="sm"
                label="New Price"
                :href="route('app.plan-price.create', [$plan->id])"
            />
        </div>
    </x-slot:header>

    <x-slot:head>
        <x-table.th label="Price"/>
        <x-table.th/>
        <x-table.th label="Available In"/>
        <x-table.th label="Subscribers" class="text-right"/>
    </x-slot:head>

    <x-slot:body>
        @foreach ($plan->planPrices as $planPrice)
            <x-table.tr>
                <x-table.td :label="implode(' / ', [
                    currency($planPrice->amount, $planPrice->currency),
                    $planPrice->recurring,
                ])" :href="route('app.plan-price.update', [$planPrice->id])"/>
                
                <x-table.td class="text-right">
                    @if ($planPrice->is_default) <x-badge label="default"/> @endif
                    @if ($planPrice->auto_renew) <x-badge label="auto-renew"/> @endif
                </x-table.td>

                <x-table.td :label="data_get(metadata()->countries($planPrice->country), 'name')"/>
                <x-table.td :label="$planPrice->accounts->count()" class="text-right"/>
            </x-table.tr>
        @endforeach
    </x-slot:body>
</x-table>
