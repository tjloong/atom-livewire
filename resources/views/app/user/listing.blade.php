<div class="max-w-screen-md mx-auto">
    <x-page-header title="Users">
        <x-button icon="plus" href="{{ route('app.user.create') }}">
            New User
        </x-button>
    </x-page-header>

    <x-table :total="$users->total()" :links="$users->links()">
        <x-slot name="head">
            <x-table head sort="name">Name</x-table>
            
            @if (auth()->user()->isRoot())
                <x-table head>Type</x-table>
            @endif
            
            @module('roles')
                <x-table head align="right">Role</x-table>
            @endmodule

            <x-table head/>
        </x-slot>

        <x-slot name="body">
            @foreach ($users as $user)
                <x-table row>
                    <x-table cell>
                        @if ($user->id === auth()->id())
                            <span>{{ $user->name }} (You)</span>
                        @else
                            <div>
                                <a href="{{ route('app.user.update', [$user->id]) }}">
                                    {{ $user->name }}
                                </a>
                                <div class="text-gray-500">
                                    {{ $user->email }}
                                </div>
                            </div>
                        @endif
                    </x-table>

                    @if (auth()->user()->isRoot())
                        <x-table cell>
                            {{ $user->account->type }}
                        </x-table>
                    @endif
                    
                    @module('roles')
                        <x-table cell class="text-right">
                            {{ $user->role->name ?? '--' }}
                        </x-table>
                    @endmodule

                    <x-table cell class="text-right">
                        <x-badge>{{ $user->status }}</x-badge>
                    </x-table>
                </x-table>
            @endforeach
        </x-slot>

        <x-slot name="empty">
            <x-empty-state title="No Users" subtitle="User list is empty"/>
        </x-slot>
    </x-table>
</div>