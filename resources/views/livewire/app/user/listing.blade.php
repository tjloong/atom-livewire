<div class="max-w-screen-xl mx-auto">
    <x-table :data="$this->users->items()">
        <x-slot:header>
            <x-table.header :label="data_get($params, 'title') ?? data_get($params, 'header') ?? 'Users'">
                <x-button size="sm" color="gray"
                    label="New User" 
                    :href="route('app.user.create', data_get($params, 'create'))"
                />
            </x-table.header>

            <x-table.searchbar :total="$this->users->total()"/>

            <x-table.toolbar>
                <div class="flex items-center gap-2">
                    <x-form.select 
                        wire:model="filters.status"
                        :options="collect(['active', 'inactive', 'blocked', 'trashed'])->map(fn($val) => [
                            'value' => $val, 
                            'label' => ucfirst($val),
                        ])"
                        placeholder="All Status"
                    />
    
                    @module('roles')
                        <x-form.select
                            wire:model="filters.role_id"
                            :options="model('role')->assignable()->get()->map(fn($role) => [
                                'value' => (string)$role->id,
                                'label' => $role->name,
                            ])"
                            placeholder="All Roles"
                        />
                    @endmodule
    
                    @module('teams')
                        <x-form.select
                            wire:model="filters.team_id"
                            :options="model('team')->assignable()->get()->map(fn($team) => [
                                'value' => (string)$team->id,
                                'label' => $team->name,
                            ])"
                            placeholder="All Teams"
                        />
                    @endmodule
                </div>

                <x-table.trashed :count="$this->query->onlyTrashed()->count()"/>
            </x-table.toolbar>
        </x-slot:header>

        <x-slot:empty>
            <x-empty-state title="No Users" subtitle="User list is empty"/>
        </x-slot:empty>
    </x-table>

    {!! $this->users->links() !!}
</div>