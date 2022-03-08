<div class="max-w-screen-md mx-auto">
    <x-page-header title="Users">
        <x-button icon="plus" href="{{ route('user.create') }}">
            New User
        </x-button>
    </x-page-header>

    <x-table :total="$users->total()" :links="$users->links()">
        <x-slot name="head">
            <x-table head sort="name">Name</x-table>
            
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
                            <div class="flex items-center gap-1">
                                <span>{{ $user->name }}</span>
                                <span class="text-xs text-gray-500">(You)</span>
                            </div>
                        @else
                            <a href="{{ route('user.update', [$user->id]) }}">
                                {{ $user->name }}
                            </a>
                            <div class="text-xs text-gray-500">
                                {{ $user->email }}
                            </div>
                        @endif
                    </x-table>
                    
                    @module('roles')
                        <x-table cell class="text-right">
                            {{ $user->role->name ?? '--' }}
                        </x-table>
                    @endmodule

                    <x-table cell class="text-right">
                        @if ($user->is_root)
                            <x-badge color="yellow">root</x-badge>
                        @endif
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