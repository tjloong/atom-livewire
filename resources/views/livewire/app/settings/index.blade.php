<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title"/>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-1/4">
            <x-sidenav wire:model="tab">
                @foreach (array_filter($this->tabs) as $item)
                    @if ($children = data_get($item, 'tabs'))
                        @if ($group = data_get($item, 'group'))
                            <x-sidenav.group :label="$group"/>
                        @endif

                        @foreach (array_filter($children) as $child)
                            <x-sidenav.item
                                :name="data_get($child, 'slug')"
                                :icon="data_get($child, 'icon')"
                                :href="data_get($child, 'href')"
                                :label="data_get($child, 'label')"
                            />
                        @endforeach
                    @else
                        <x-sidenav.item
                            :name="data_get($child, 'slug')"
                            :icon="data_get($item, 'icon')"
                            :href="data_get($item, 'href')"
                            :label="data_get($item, 'label')"
                        />
                    @endif
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:w-3/4">
            @livewire($livewire, key($tab))
        </div>
    </div>
</div>