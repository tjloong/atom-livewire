<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$plan->name" back>
        <x-button.delete inverted
            title="Delete Plan"
            message="Are you sure to DELETE this plan?"
        />
    </x-page-header>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-1/4">
            <x-sidenav wire:model="tab">
                @foreach ($this->tabs as $item)
                    <x-sidenav.item 
                        :name="data_get($item, 'slug')" 
                        :label="data_get($item, 'label')"
                        :count="data_get($item, 'count')"
                        :icon="false"
                    />
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:w-3/4">
            @if ($com = data_get(collect($this->tabs)->firstWhere('slug', $tab), 'livewire'))
                @livewire(lw($com), compact('plan'), key($tab))
            @else
                @livewire(lw('app.plan.update.'.$tab), compact('plan'), key($tab))
            @endif
        </div>
    </div>
</div>