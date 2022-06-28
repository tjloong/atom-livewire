<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$plan->name" back>
        <x-button.delete inverted
            title="Delete Plan"
            message="Are you sure to delete this plan?"
        />
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-7">
            @livewire('atom.plan.form', compact('plan'), key('plan-form'))
        </div>

        <div class="md:col-span-5">
            <x-box>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <div>Prices</div>
        
                        @if ($plan->planPrices->count())
                            <x-button :href="route('app.plan-price.create', [$plan->id])" icon="plus" size="xs">
                                New Price
                            </x-button>
                        @endif
                    </div>
                </x-slot:header>
        
                <div class="grid divide-y">
                    @forelse ($plan->planPrices as $price)
                        <a href="{{ route('app.plan-price.update', [$price->id]) }}" class="grid gap-1 py-2 px-4 text-gray-800 hover:bg-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="grid gap-1">
                                    <div class="flex items-center gap-1 text-base">
                                        <div class="font-medium text-gray-500">{{ $price->currency }}</div>
                                        <div class="font-semibold">{{ currency($price->amount) }}</div>
                                        <div class="font-medium text-gray-500">/{{ $price->recurring }}</div>
                                    </div>
                                </div>
    
                                @if ($price->is_default)
                                    <x-badge>default</x-badge>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 justify-between text-sm text-gray-500">
                                <div>
                                    @if ($cn = optional(metadata()->countries($price->country))->name)
                                        Available in {{ $cn }}
                                    @endif
                                </div>
                                <div class="text-right">
                                    {{ $price->accounts()->count() }} {{ __('Subscribed') }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <x-empty-state title="No prices found" subtitle="No price defined for this plan">
                            <x-button href="{{ route('app.plan-price.create', [$plan->id]) }}" icon="plus">
                                Create Price
                            </x-button>
                        </x-empty-state>
                    @endforelse
                </div>
            </x-box>
        </div>
    </div>
</div>