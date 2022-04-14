<div class="grid gap-6 md:grid-cols-12">
    @if ($this->tabs->count() > 1)
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    @if ($group = $item['group'] ?? null)
                        <x-sidenav :group="$group">
                            @foreach ($item['tabs'] as $child)
                                <x-sidenav item :icon="data_get($child, 'icon')" :name="data_get($child, 'slug', $child)">
                                    {{ data_get($child, 'label') ?? str($child)->headline() }}
                                </x-sidenav>
                            @endforeach
                        </x-sidenav>
                    @else
                        <x-sidenav item :icon="data_get($item, 'icon')" :name="data_get($item, 'slug', $item)">
                            {{ data_get($item, 'label') ?? str($item)->headline() }}
                        </x-sidenav>
                    @endif
                @endforeach
            </x-sidenav>
        </div>
    @endif

    <div class="{{ $this->tabs->count() > 1 ? 'md:col-span-9' : 'md:col-span-12' }}">
        @if ($component = livewire_name('account/'.$tab))
            @livewire($component, key($tab))
        @endif
    </div>
</div>
