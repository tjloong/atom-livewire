<div class="grid gap-6 md:grid-cols-12">
    <div class="md:col-span-3">
        <x-sidenav wire:model="tab" class="text-sm">
            @foreach ($this->tabs as $item)
                <x-sidenav item name="{{ $item['value'] }}">
                    <div class="flex items-center gap-2">
                        <x-icon
                            name="{{ $item['completed'] ? 'check-circle' : 'radio-circle' }}"
                            class="{{ $item['completed'] ? 'text-green-500' : 'text-gray-400' }}"
                            type="{{ $item['completed'] ? 'solid' : 'regular' }}"
                            size="18px"
                        />
                        <div>{{ $item['label'] }}</div>
                    </div>
                </x-sidenav>
            @endforeach
        </x-sidenav>
    </div>

    <div class="md:col-span-9">
        @if ($component = get_livewire_component($tab, 'onboarding'))
            @livewire($component, compact('signup', 'tenant'), key($tab))
        @endif
    </div>
</div>
