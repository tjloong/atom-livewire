<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$plan->name" back>
        <x-button.delete inverted
            title="Delete Plan"
            message="Are you sure to delete this plan?"
        />
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                @foreach ([
                    'prices' => 'Plan Prices '.($plan->planPrices->count()
                        ? '('.$plan->planPrices->count().')'
                        : ''
                    ),
                    'info' => 'Plan Information',
                ] as $key => $val)
                    <x-sidenav.item :name="$key" :label="$val"/>
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @if ($com = livewire_name('app/plan/update/'.$tab))
                @livewire($com, compact('plan'), key($tab))
            @endif
        </div>
    </div>
</div>