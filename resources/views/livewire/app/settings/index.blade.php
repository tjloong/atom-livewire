<div class="flex flex-col divide-y md:flex-row md:divide-x md:h-full">
    <div class="shrink-0 bg-white p-5 md:w-72">
        <x-sidenav wire:model="tab" heading="settings.heading.settings">
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
        @if (str($tab)->is('label/*'))
            @livewire('app.settings.label', [
                'type' => str($tab)->replaceFirst('label/', '')->toString(),
            ], key($tab))
        @elseif (str($tab)->contains('/'))
            @livewire('app.settings.'.(str($tab)->replace('/', '.')->toString()), key($tab))
        @else
            @livewire('app.settings.'.$tab, key($tab))
        @endif
    </div>
</div>