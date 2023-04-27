<div class="max-w-screen-xl mx-auto">
    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-1/4">
            <x-box :header="$this->title">
                <div class="p-2">
                    <x-sidenav>
                        @foreach ($this->filteredTabs as $item)
                            @if ($children = data_get($item, 'tabs'))
                                @if ($group = data_get($item, 'group'))
                                    <x-sidenav.group :label="$group"/>
                                @endif
        
                                @foreach ($children as $child)
                                    <x-sidenav.item
                                        :icon="data_get($child, 'icon')"
                                        :label="data_get($child, 'label')"
                                        :href="route('app.settings', [data_get($child, 'slug')])"
                                    />
                                @endforeach
                            @elseif (!data_get($item, 'group'))
                                <x-sidenav.item
                                    :icon="data_get($item, 'icon')"
                                    :label="data_get($item, 'label')"
                                    :href="route('app.settings', [data_get($item, 'slug')])"
                                />
                            @endif
                        @endforeach
                    </x-sidenav>
                </div>
            </x-box>
        </div>

        <div class="md:w-3/4">
            @if ($com = lw(
                data_get(tabs($this->tabs, $tab), 'livewire')
                ?? 'app.settings.'.$tab
            ))
                @livewire($com, key($tab))
            @endif
        </div>
    </div>
</div>