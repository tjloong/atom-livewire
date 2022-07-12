<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Site Settings"/>
    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    @if ($group = data_get($item, 'group'))
                        <x-sidenav.group :label="$group"/>
                    @endif

                    @foreach (data_get($item, 'tabs') as $child)
                        <x-sidenav.item
                            :icon="data_get($child, 'icon')"
                            :name="is_string($child) ? $child : data_get($child, 'slug')"
                            :label="is_string($child) ? str()->headline($child) : data_get($child, 'label')"
                        />
                    @endforeach
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            <div class="grid gap-6">
                @if ($component = livewire_name('app/site-settings/'.$tab))
                    @livewire($component, key($tab))
                @endif
            </div>
        </div>
    </div>
</div>
