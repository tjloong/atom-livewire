<div class="max-w-screen-xl mx-auto">
    <x-box header="Taxes">
        <x-slot:buttons>
            <x-button size="sm" color="gray"
                label="New Tax"
                :href="route('app.tax.create')"
            />
        </x-slot:buttons>
    
        <div class="grid divide-y">
            @forelse ($this->taxes as $tax)
                <div class="py-2 px-4 flex items-center gap-3 flex-wrap">
                    <a class="grow" wire:click="open(@js($tax->id))">
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
