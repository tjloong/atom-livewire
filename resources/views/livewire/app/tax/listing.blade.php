<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Taxes">
        <x-button label="New Tax" :href="route('app.tax.create')"/>
    </x-page-header>

    <x-box>
        <div class="flex flex-col divide-y">
            @forelse ($this->taxes as $tax)
                <div class="py-2 px-4 flex items-center gap-3 flex-wrap">
                    <a href="{{ route('app.tax.update', [$tax->id]) }}" class="grow">
                        {{ $tax->label }}

                        @if (!$tax->is_active) <x-badge label="inactive"/> @endif
                    </a>

                    <div class="shrink-0 text-sm text-gray-500 font-medium">
                        {{ collect([$tax->region, $tax->country])->filter()->join(', ') }}
                    </div>
                </div>
            @empty
                <x-empty-state title="No Taxes" subtitle="The taxes list is empty."/>
            @endforelse
        </div>
    </x-box>
</div>
