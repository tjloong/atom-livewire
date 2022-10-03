<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$role->name" back>
        <div class="flex items-center gap-2">
            @if ($role->slug !== 'admin')
                <x-button.confirm color="gray" 
                    label="Duplicate"
                    title="Duplicate Role"
                    message="Are you sure to duplicate this role?"
                    callback="duplicate"
                />
            @endif

            <x-button.delete inverted
                title="Delete Role"
                message="Are you sure to delete this role?"
            />
        </div>
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                @foreach ([
                    ['slug' => 'info', 'label' => 'Role Information'],
                    enabled_module('permissions')
                        ? ['slug' => 'permissions', 'label' => 'Permissions', 'count' => $role->permissions()->granted()->count()]
                        : null,
                    ['slug' => 'users', 'label' => 'Users', 'count' => $role->users()->count()],
                ] as $item)
                    <x-sidenav.item 
                        :name="data_get($item, 'slug')" 
                        :label="data_get($item, 'label')"
                        :count="data_get($item, 'count')"
                        :icon="false"
                    />
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @if ($com = lw([
                'info' => 'app.role.update.info',
                'users' => 'app.user.listing',
                'permissions' => 'app.permission.listing',
            ][$tab]))
                @livewire($com, compact('role'), key($tab))
            @endif
        </div>
    </div>
</div>
