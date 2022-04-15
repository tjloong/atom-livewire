<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$plan->name" back>
        <x-button inverted icon="trash" color="red" x-on:click="$dispatch('confirm', {
            title: 'Delete Plan',
            message: 'Are you sure to delete this plan?',
            type: 'error',
            onConfirmed: () => $wire.delete(),    
        })">
            Delete
        </x-button>
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
        
                        @if ($plan->prices->count())
                            <x-button :href="route('app.plan-price.create', [$plan->id])" icon="plus" size="xs">
                                New Price
                            </x-button>
                        @endif
                    </div>
                </x-slot:header>
        
                <div class="grid divide-y">
                    @forelse ($plan->prices as $price)
                        <a href="{{ route('app.plan-price.update', [$price->id]) }}" class="py-2 px-4 text-gray-800 hover:bg-gray-100">
                            <div class="flex items-start justify-between">
                                <div class="grid gap-1">
                                    <div class="flex items-center gap-1 text-base">
                                        <div class="font-medium text-gray-500">{{ $price->currency }}</div>
                                        <div class="font-semibold">{{ currency($price->amount) }}</div>
                                        <div class="font-medium text-gray-500">{{ $price->recurring }}</div>
                                    </div>
    
                                    @if ($cn = optional(metadata()->countries($price->country))->name)
                                        <div class="text-sm text-gray-500">Available in {{ $cn }}</div>
                                    @endif
                                </div>
    
                                @if ($price->is_default)
                                    <x-badge>default</x-badge>
                                @endif
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