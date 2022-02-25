<div class="max-w-screen-md mx-auto">
    <x-page-header title="Roles">
        <x-button icon="plus" href="{{ route('role.create') }}">
            New Role
        </x-button>
    </x-page-header>

    <x-table :total="$roles->total()" :links="$roles->links()">
        <x-slot name="head">
            <x-table head sort="name">Name</x-table>
            <x-table head align="right">Users</x-table>
        </x-slot>

        <x-slot name="body">
        @foreach ($roles as $role)
            <x-table row>
                <x-table cell>
                    <a href="{{ route('role.update', [$role->id]) }}">
                        {{ $role->name }}
                    </a>
                </x-table>
                
                <x-table cell class="text-right">
                    {{ $role->users_count }} {{ str('user')->plural($role->users_count) }}
                </x-table>
            </x-table>
        @endforeach
        </x-slot>
    </x-table>
</div>