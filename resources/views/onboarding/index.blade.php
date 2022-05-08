<div class="grid gap-6 md:grid-cols-12">
    @if ($this->steps->count() > 1)
        <div class="md:col-span-3">
            <x-sidenav wire:model="step">
                @foreach ($this->steps as $item)
                    @if ($val = data_get($item, 'value'))
                        <x-sidenav.item :name="$val" :active="$step === $val">
                            <div class="flex items-center gap-2">
                                <x-icon
                                    name="{{ $item['completed'] ? 'check-circle' : 'radio-circle' }}"
                                    class="{{ $item['completed'] ? 'text-green-500' : 'text-gray-400' }}"
                                    type="{{ $item['completed'] ? 'solid' : 'regular' }}"
                                    size="18px"
                                />
                                <div>{{ $item['label'] }}</div>
                            </div>
                        </x-sidenav.item>
                    @endif
                @endforeach
            </x-sidenav>
        </div>
    @endif

    <div class="{{ $this->steps->count() > 1 ? 'md:col-span-9' : 'md:col-span-12' }}">
        @if ($component = livewire_name('onboarding/'.$step))
            @livewire($component, key($step))
        @endif
    </div>
</div>
