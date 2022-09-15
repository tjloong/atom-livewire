<div class="max-w-screen-lg mx-auto">
    <x-table 
        header="Roles"
        :total="$this->roles->total()" 
        :links="$this->roles->links()"
    >
        <x-slot:header-buttons>
            <x-button size="sm" label="New Role" :href="route('app.role.create')"/>
        </x-slot:header-buttons>

        <x-slot:head>
            <x-table.th label="Name" sort="name"/>
            @module('permissions') <x-table.th label="Permissions" class="text-right"/> @endmodule
            <x-table.th label="Users" class="text-right"/>
        </x-slot:head>

        <x-slot:body>
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
        </x-slot:body>
    </x-table>
</div>