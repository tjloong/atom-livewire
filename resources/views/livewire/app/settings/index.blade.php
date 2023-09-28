<div class="flex flex-col divide-y md:flex-row md:divide-x md:h-full">
    <div class="shrink-0 bg-white p-5 md:w-72">
        <x-sidenav wire:model="tab" title="atom::settings.sidenav.title">
            @foreach ($this->tabs as $item)
                <x-sidenav.group :label="data_get($item, 'group')"/>

                @foreach (data_get($item, 'tabs') as $child)
                    <x-sidenav.item
                        :name="data_get($child, 'slug')"
                        :icon="data_get($child, 'icon')"
                        :label="data_get($child, 'label')"/>
                @endforeach
            @endforeach
        </x-sidenav>
    </div>

    <div class="grow p-6">
        @livewire(
            data_get($this->component, 'name'), 
            ['params' => data_get($this->component, 'params')],
            key($tab),
        )
    </div>
</div>