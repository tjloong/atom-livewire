<div class="flex flex-col gap-6 md:flex-row">
    @if ($this->steps->count() > 1)
        <div class="md:w-1/4">
            <x-sidenav wire:model="step">
                @foreach ($this->steps as $item)
                    @if ($val = data_get($item, 'value'))
                        <x-sidenav.item :name="$val" :active="$step === $val" >
                            <div class="flex items-center gap-2">
                                @if (data_get($item, 'completed'))
                                    <x-icon name="circle-check" class="text-green-500" size="18"/>
                                @else
                                    <x-icon name="circle-dot" class="text-gray-400" size="18"/>
                                @endif
                                <div>{{ data_get($item, 'label') }}</div>
                            </div>
                        </x-sidenav.item>
                    @endif
                @endforeach
            </x-sidenav>
        </div>
    @endif

    <div class="{{ $this->steps->count() > 1 ? 'md:w-3/4' : 'w-full' }}">
        @if (
            $com = data_get($this->steps->firstWhere('value', $step), 'livewire')
                ?? 'app.onboarding.'.$step
        )
            @livewire(lw($com), ['onboarding' => true], key($step))
        @endif
    </div>
</div>
