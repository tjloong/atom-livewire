<div class="max-w-screen-lg mx-auto">
    <x-table>
        <x-slot:header>
            <x-table.header label="Roles">
                <x-button size="sm" label="New Role" :href="route('app.role.create')"/>
            </x-table.header>

            <x-table.searchbar :total="$this->roles->total()"/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Name" sort="name"/>
            @module('permissions') <x-table.th label="Permissions" class="text-right"/> @endmodule
            <x-table.th label="Users" class="text-right"/>
        </x-slot:thead>

        @foreach ($this->roles as $role)
            <x-table.tr>
                <x-table.td :label="$role->name" :href="route('app.role.update', [$role->id])"/>

                @module('permissions')
                    <x-table.td class="text-right">
                        @if ($role->slug === 'admin') --
                        @else
                            @php $permissionsCount = $role->permissions()->granted()->count() @endphp
                            {{ __(':count '.str()->plural('permission', $permissionsCount), ['count' => $permissionsCount]) }}
                        @endif
                    </x-table.td>    
                @endmodule

                <x-table.td class="text-right">
                    {{ __(':count '.str()->plural('user', $role->users_count), ['count' => $role->users_count]) }}
                </x-table.td>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->roles->links() !!}
</div>