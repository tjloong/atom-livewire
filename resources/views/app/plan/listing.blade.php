<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Plans">
        <x-button icon="plus" href="{{ route('plan.create') }}">
            New Plan
        </x-button>
    </x-page-header>

    <div class="grid gap-4 md:grid-cols-3">
        @forelse ($plans as $plan)
            <a href="{{ route('plan.update', [$plan->id]) }}">
                <x-box class="hover:border-theme">
                    <div class="p-4 grid gap-2">
                        <div class="flex justify-between gap-2">
                            <div class="text-base font-semibold">{{ $plan->name }}</div>
                            <div class="flex-shrink-0">
                                <x-badge>{{ $plan->is_active ? 'active' : 'inactive' }}</x-badge>
                            </div>
                        </div>
                        <div class="grid">
                            @foreach ($plan->prices as $price)
                                <div>
                                    <span class="font-medium text-gray-500">{{ $price->currency }}</span>
                                    <span class="font-semibold text-gray-800">{{ currency($price->amount) }}</span>
                                    <span class="font-medium text-gray-500">{{ str($price->recurring)->headline() }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-box>
            </a>
        @empty
            <div class="md:col-span-3">
                <x-empty-state title="No plan found"/>
            </div>
        @endforelse
    </div>
</div>