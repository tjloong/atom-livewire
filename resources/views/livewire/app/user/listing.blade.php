<div class="max-w-screen-lg mx-auto">
    <x-table>
        <x-slot:header>
            <x-table.header label="Users">
                <x-button size="sm"
                    label="New User" 
                    :href="route('app.user.create', [
                        'account' => optional($account)->id,
                        'role' => optional($role)->id,
                    ])"
                />
            </x-table.header>

            <x-table.searchbar :total="$this->users->total()"/>

            @if ($this->trashedCount || data_get($filters, 'status') === 'trashed')
                <x-table.toolbar>
                    <x-tab wire:model="filters.status">
                        <x-tab.item :name="null" label="All"/>
                        <x-tab.item name="trashed" label="Trashed" :count="$this->trashedCount"/>
                    </x-tab>

                    @if ($this->trashedCount)
                        <x-button.trashed/>
                    @endif
                </x-table.toolbar>
            @endif
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Name" sort="name"/>
            @module('roles') <x-table.th label="Role" class="text-right"/> @endmodule
            <x-table.th label="Created Date" class="text-right"/>
            <x-table.th/>
        </x-slot:thead>

        @foreach ($this->users as $user)
            <x-table.tr>
                <x-table.td>
                    @if ($user->id === auth()->id())
                        <span>{{ $user->name }} ({{ __('You') }})</span>
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
                </x-table.td>

                @module('roles')
                    <x-table.td :label="data_get($user->role, 'name')" class="text-right"/>
                @endmodule

                <x-table.td :date="$user->created_at" class="text-right"/>
                <x-table.td :status="$user->status" class="text-right"/>
            </x-table.tr>
        @endforeach

        <x-slot:empty>
            <x-empty-state title="No Users" subtitle="User list is empty"/>
        </x-slot:empty>
    </x-table>

    {!! $this->users->links() !!}
</div>