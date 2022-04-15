<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Site Settings"/>
    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    <x-sidenav :group="data_get($item, 'group')">
                        @foreach (data_get($item, 'tabs') as $child)
                            <x-sidenav item :name="data_get($child, 'slug')">
                                {{ data_get($child, 'label') ?? str()->headline(data_get($child, 'slug')) }}
                            </x-sidenav>
                        @endforeach
                    </x-sidenav>
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
