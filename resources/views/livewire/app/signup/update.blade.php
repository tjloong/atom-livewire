<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$user->name" back>
        <div class="flex items-center gap-2">
            @livewire(atom_lw('app.user.btn-block'), compact('user'), key('block'))
            @livewire(atom_lw('app.user.btn-delete'), compact('user'), key('delete'))
        </div>            
    </x-page-header>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-1/4">
            <x-sidenav>
                @foreach ($this->tabs as $item)
                    @if ($children = data_get($item, 'tabs'))
                        @if ($group = data_get($item, 'group'))
                            <x-sidenav.group :label="$group"/>
                        @endif

                        @foreach ($children as $child)
                            <x-sidenav.item
                                :icon="data_get($child, 'icon')"
                                :label="data_get($child, 'label')"
                                :href="route('app.signup.update', [$user->id, data_get($child, 'slug')])"
                            />
                        @endforeach
                    @else
                        <x-sidenav.item
                            :icon="data_get($item, 'icon')"
                            :label="data_get($item, 'label')"
                            :href="route('app.signup.update', [$user->id, data_get($item, 'slug')])"
                        />
                    @endif
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:w-3/4 flex flex-col gap-6">
            @if ($com = $tab ? data_get(tabs($this->tabs, $tab), 'livewire') : null)
                @livewire(
                    is_string($com) ? atom_lw($com) : atom_lw(data_get($com, 'name')),
                    is_string($com) ? compact('user') : data_get($com, 'data', []),
                    key($tab)
                )
            @endif
        </div>
    </div>
</div>