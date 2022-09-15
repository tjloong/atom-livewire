<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$team->name" back>
        <x-button.delete inverted
            title="Delete Team"
            message="Are you sure to delete this team?"
        />
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                @foreach ([
                    ['slug' => 'info', 'label' => 'Team Information'],
                    ['slug' => 'users', 'label' => 'Users', 'count' => $team->users()->count()],
                ] as $item)
                    <x-sidenav.item
                        :name="data_get($item, 'slug')"
                        :label="data_get($item, 'label')"
                        :count="data_get($item, 'count')"
                    />
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @if ($com = lw('app.team.update.'.$tab))
                @livewire($com, compact('team'), key($tab))
            @endif
        </div>
    </div>
</div>