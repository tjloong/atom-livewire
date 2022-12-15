<div class="max-w-screen-xl mx-auto">
    <x-box header="Taxes">
        <x-slot:buttons>
            <x-button size="sm" color="gray"
                label="New Tax"
                wire:click="open"
            />
        </x-slot:buttons>
    
        <div class="grid divide-y">
            @forelse ($this->taxes as $tax)
                <div class="py-2 px-4 flex items-center gap-3 flex-wrap">
                    <a class="grow" wire:click="open(@js($tax->id))">
                        {{ $tax->label }}

                        @if (!$tax->is_active)
                            <span class="text-sm text-gray-400 font-medium">{{ __('inactive') }}</span>
                        @endif
                    </a>

                    <div class="shrink-0 text-sm text-gray-500 font-medium">
                        {{ collect([
                            $tax->region, 
                            data_get(metadata()->countries($tax->country), 'name')
                        ])->filter()->join(', ') }}
                    </div>

                    <x-close.delete class="shrink-0"
                        title="Delete Tax"
                        message="Are you sure to delete this tax?"
                        :params="$tax->id"
                    />
                </div>
            @empty
                <x-empty-state
                    title="No Taxes"
                    subtitle="The taxes list is empty."
                >
                    <x-button color="gray"
                        label="New Tax"
                        wire:click="open"
                    />
                </x-empty-state>
            @endforelse
        </div>

        @if ($onboarding)
            <x-slot:foot>
                <x-button color="green" icon="check"
                    label="Continue"
                    wire:click="$emitUp('next')"
                />
            </x-slot:foot>
        @endif
    </x-box>

    @livewire(lw('app.preferences.tax-form-modal'))
</div>
