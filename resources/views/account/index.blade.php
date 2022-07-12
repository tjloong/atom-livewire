<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Account Settings"/>
    
    <div class="grid gap-6 md:grid-cols-12">
        @if ($this->tabs->count() > 1)
            <div class="md:col-span-3">
                <x-sidenav wire:model="tab">
                    @foreach ($this->tabs as $item)
                        @if ($children = data_get($item, 'tabs'))
                            @if ($group = data_get($item, 'group'))
                                <x-sidenav.group :label="$group"/>
                            @endif 
                            
                            @foreach ($children as $child)
                                <x-sidenav.item
                                    :icon="data_get($child, 'icon')"
                                    :name="is_string($child) ? $child : data_get($child, 'slug')"
                                    :label="is_string($child) ? str()->headline($child) : data_get($child, 'label')"
                                />
                            @endforeach
                        @else
                            <x-sidenav.item
                                :icon="data_get($item, 'icon')"
                                :name="is_string($item) ? $item : data_get($item, 'slug')"
                                :label="is_string($item) ? str()->headline($item) : data_get($item, 'label')"
                            />
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
</div>
