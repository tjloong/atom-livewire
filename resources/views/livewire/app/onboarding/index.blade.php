<div class="grid gap-6 md:grid-cols-12">
    @if ($this->steps->count() > 1)
        <div class="md:col-span-3">
            <x-sidenav wire:model="step">
                @foreach ($this->steps as $item)
                    @if ($val = data_get($item, 'value'))
                        <x-sidenav.item :name="$val" :active="$step === $val" >
                            <div class="flex items-center gap-2">
                                @if (data_get($item, 'completed'))
                                    <x-icon name="circle-check" class="text-green-500" size="18px"/>
                                @else
                                    <x-icon name="circle-dot" class="text-gray-400" size="18px"/>
                                @endif
                                <div>{{ data_get($item, 'label') }}</div>
                            </div>
                        </x-sidenav.item>
                    @endif
                @endforeach
            </x-sidenav>
        </div>
    @endif

    <div class="{{ $this->steps->count() > 1 ? 'md:col-span-9' : 'md:col-span-12' }}">
        @livewire($livewire, [
            'onboarding' => true,
        ], key($step))
    </div>
</div>
