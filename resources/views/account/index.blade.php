<div class="grid gap-6 md:grid-cols-12">
    @if ($this->tabs->count() > 1)
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    @if ($children = data_get($item, 'tabs'))
                        <x-sidenav.group :name="data_get($item, 'group')">
                            @foreach ($children as $child)
                                @if ($slug = data_get($child, 'slug'))
                                    <x-sidenav.item :icon="data_get($child, 'icon')" :name="$slug">
                                        {{ data_get($child, 'label') ?? str($slug)->headline() }}
                                    </x-sidenav.item>
                                @elseif (is_string($child))
                                    <x-sidenav.item :name="$child">
                                        {{ str($child)->headline() }}
                                    </x-sidenav.item>
                                @endif
                            @endforeach
                        </x-sidenav.group>
                    @elseif ($slug = data_get($item, 'slug'))
                        <x-sidenav.group>
                            <x-sidenav.item :icon="data_get($item, 'icon')" :name="$slug">
                                {{ data_get($item, 'label') ?? str($slug)->headline() }}
                            </x-sidenav.item>
                        </x-sidenav.group>
                    @elseif (is_string($item))
                        <x-sidenav.group>
                            <x-sidenav.item :name="$item">
                                {{ str($item)->headline() }}
                            </x-sidenav.item>
                        </x-sidenav.group>
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
