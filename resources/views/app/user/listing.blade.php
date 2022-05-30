<div class="max-w-screen-md mx-auto">
    @if ($isFullpage)
        <x-page-header title="Users">
            <x-button.create label="New User" :href="route('app.user.create')"/>
        </x-page-header>
    @endif

    <x-table :total="$this->users->total()" :links="$this->users->links()">
        @if ($account)
            <x-slot:header>
                <div class="flex items-center gap-4 justify-between">
                    <div>{{ __('Users') }}</div>
                    <x-button.create size="sm"
                        label="New User" 
                        :href="route('app.user.create', ['account' => $account->id])"
                    />
                </div>
            </x-slot:header>
        @endif

        @if ($this->tabs)
            <x-slot:toolbar :trashed="data_get($filters, 'status') === 'trashed'">
                <x-tab wire:model="filters.status">
                @foreach ($this->tabs as $item)
                    <x-tab item :name="data_get($item, 'slug')" :label="data_get($item, 'label')"/>
                @endforeach
                </x-tab>
            </x-slot:toolbar>
        @endif

        <x-slot:head>
            <x-table.th sort="name">{{ __('Name') }}</x-table.th>
            
            @if (auth()->user()->isAccountType('root'))
                <x-table.th>{{ __('Type') }}</x-table.th>
            @endif
            
            @module('roles')
                <x-table.th class="text-right">{{ __('Role') }}</x-table.th>
            @endmodule

            <x-table.th/>
        </x-slot:head>

        <x-slot:body>
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

                    @if (auth()->user()->isAccountType('root'))
                        <x-table.td>
                            {{ $user->account->type }}
                        </x-table.td>
                    @endif
                    
                    @module('roles')
                        <x-table.td class="text-right">
                            {{ $user->role->name ?? '--' }}
                        </x-table.td>
                    @endmodule

                    <x-table.td class="text-right">
                        <x-badge>{{ $user->status }}</x-badge>
                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-slot:body>

        <x-slot:empty>
            <x-empty-state :title="__('No Users')" :subtitle="__('User list is empty')"/>
        </x-slot:empty>
    </x-table>
</div>