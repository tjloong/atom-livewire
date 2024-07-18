<div class="flex flex-col divide-y md:flex-row md:divide-x md:divide-y-0 md:h-full">
    <div class="shrink-0 bg-white p-5 md:w-72">
        <x-sidenav wire:model="tab" heading="settings.heading.settings">
            @foreach ($this->tabs as $item)
                <x-sidenav.group :label="get($item, 'group')"/>

                @if ($children = get($item, 'tabs', []))
                    @foreach ($children as $child)
                        <x-sidenav.item :value="get($child, 'slug')" :icon="get($child, 'icon')" :label="get($child, 'label')"/>
                    @endforeach
                @else
                    <x-sidenav.item :value="get($item, 'slug')" :icon="get($item, 'icon')" :label="get($item, 'label')"/>
                @endif
            @endforeach
        </x-sidenav>
    </div>

    <div class="grow p-6">
        @livewire(
            get($this->livewire, 'name'),
            get($this->livewire, 'params'),
            key($tab)
        )
    </div>
</div>