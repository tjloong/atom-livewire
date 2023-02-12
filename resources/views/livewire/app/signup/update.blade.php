<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$user->name" back>
        @tier('root')
            <div class="flex items-center gap-2">
                @can('user.signup.block')
                    @livewire(lw('app.user.update.block'), compact('user'), key('block'))
                @endcan

                @can('user.signup.delete')
                    @livewire(lw('app.user.update.delete'), compact('user'), key('delete'))
                @endcan
            </div>            
        @endtier
    </x-page-header>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-1/4">
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    @if ($children = data_get($item, 'tabs'))
                        @if ($group = data_get($item, 'group'))
                            <x-sidenav.group :label="$group"/>
                        @endif

                        @foreach ($children as $child)
                            <x-sidenav.item
                                :icon="data_get($child, 'icon')"
                                :name="data_get($child, 'slug')"
                                :label="data_get($child, 'label')"
                                :href="data_get($child, 'href') ?? route('app.signup.update', [$user->id, data_get($child, 'slug')])"
                            />
                        @endforeach
                    @else
                        <x-sidenav.item
                            :icon="data_get($item, 'icon')"
                            :name="data_get($item, 'slug')"
                            :label="data_get($item, 'label')"
                            :href="data_get($item, 'href') ?? route('app.signup.update', [$user->id, data_get($item, 'slug')])"
                        />
                    @endif
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:w-3/4 flex flex-col gap-6">
            @if ($livewire = data_get($this->flatTabs->firstWhere('slug', $this->tab), 'livewire'))
                @if (is_string($livewire)) @livewire(lw($livewire), compact('user'), key($tab))
                @else @livewire(lw(data_get($livewire, 'name')), data_get($livewire, 'data'), key($tab))
                @endif
            @endif
        </div>
    </div>
</div>