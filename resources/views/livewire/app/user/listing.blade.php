<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Users">
        @if (tenant() && enabled_module('invitations'))
            <x-button icon="add" label="Invite User" :href="route('app.invitation.create')"/>
        @else
            <x-button icon="add" label="New User" :href="route('app.user.create')"/>
        @endif
    </x-page-header>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>

            <x-table.toolbar>
                <div class="flex items-center gap-2">
                    <x-form.select  :label="false"
                        wire:model="filters.status"
                        :options="data_get($this->options, 'status')"
                        placeholder="All Status"
                    />

                    @if ($roles = data_get($this->options, 'roles'))
                        <x-form.select :label="false"
                            wire:model="filters.is_role"
                            :options="$roles"
                            placeholder="All Roles"
                        />
                    @endif
    
                    @if ($teams = data_get($this->options, 'teams'))
                        <x-form.select :label="false"
                            wire:model="filters.in_team"
                            :options="$teams"
                            placeholder="All Teams"
                        />
                    @endif
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