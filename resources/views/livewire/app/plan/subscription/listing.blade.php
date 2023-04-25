<div class="{{ tier('root') ? 'max-w-screen-xl' : 'max-w-screen-lg' }} mx-auto">
    <x-page-header :title="$this->title"/>

    <x-table :data="$this->table">
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

                    @if ($planSlug = data_get($this->filters, 'plan'))
                        <x-form.select wire:model="filters.price" :label="false"
                            :options="model('plan_price')->whereHas('plan', fn($q) => $q->where('slug', $planSlug))->get()->map(fn($price) => [
                                'value' => $price->id,
                                'label' => $price->name,
                            ])"
                            placeholder="All Prices"
                        />
                    @endif
                </div>
            </x-table.toolbar>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>