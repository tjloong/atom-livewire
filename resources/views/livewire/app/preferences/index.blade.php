<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title"/>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    @php $children = collect(data_get($item, 'tabs'))->filter(fn($val) => !data_get($val, 'disabled', false)) @endphp
                    @if ($children->count())
                        <x-sidenav.group :label="data_get($item, 'group')"/>

                        @foreach ($children as $child)
                            <x-sidenav.item :icon="false"
                                :href="route('app.preferences', [data_get($child, 'slug')])"
                                :label="data_get($child, 'label')"
                            />
                        @endforeach
                    @endif
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @php 
                $com = $this->flatTabs->firstWhere('slug', $tab)->get('livewire') 
                    ?? 'app.preferences.'.$tab
            @endphp

            @livewire(
                is_string($com) ? lw($com) : lw(data_get($com, 'name')),
                data_get($com, 'data', []),
                key($tab)
            )
        </div>
    </div>
</div>