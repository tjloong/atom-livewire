<div class="max-w-screen-md mx-auto">
    <x-page-header title="Roles">
        <x-button icon="plus" href="{{ route('role.create', ['back' => url()->current()]) }}">
            New Role
        </x-button>
    </x-page-header>

    <x-table :total="$roles->total()" :links="$roles->links()">
        <x-slot name="head">
            <x-table.head sort="name">Name</x-table.head>
            <x-table.head align="right">Users</x-table.head>
        </x-slot>

        <x-slot name="body">
        @foreach ($roles as $role)
            <x-table.row>
                <x-table.cell>
                    <a href="{{ route('role.update', [$role->id]) }}">
                        {{ $role->name }}
                    </a>
                </x-table.cell>
                
                <x-table.cell class="text-right">
                    {{ $role->users_count }} {{ \Illuminate\Support\Str::of('user')->plural($role->users_count) }}
                </x-table.cell>
            </x-table.row>
        @endforeach
        </x-slot>
    </x-table>
</div>