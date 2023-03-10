<div class="max-w-screen-xl mx-auto">
    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.header label="Users">
                <x-button size="sm" color="gray"
                    label="New User" 
                    :href="route('app.user.create')"
                />
            </x-table.header>

            <x-table.searchbar :total="$this->paginator->total()"/>

            <x-table.toolbar>
                <div class="flex items-center gap-2">
                    <x-form.select  :label="false"
                        wire:model="filters.status"
                        :options="collect(['active', 'inactive', 'blocked', 'trashed'])->map(fn($val) => [
                            'value' => $val, 
                            'label' => ucfirst($val),
                        ])"
                        placeholder="All Status"
                    />
    
                    @module('roles')
                        <x-form.select :label="false"
                            wire:model="filters.is_role"
                            :options="model('role')->readable()->get()->map(fn($role) => [
                                'value' => $role->slug,
                                'label' => $role->name,
                            ])"
                            placeholder="All Roles"
                        />
                    @endmodule
    
                    @module('teams')
                        <x-form.select :label="false"
                            wire:model="filters.in_team"
                            :options="model('team')->readable()->get()->map(fn($team) => [
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

    {!! $this->paginator->links() !!}
</div>