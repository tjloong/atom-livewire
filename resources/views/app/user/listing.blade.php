<div class="max-w-screen-md mx-auto">
    @route('app.user.listing')
        <x-page-header :title="__('Users')">
            <x-button icon="plus" :href="route('app.user.create')">
                {{ __('New User') }}
            </x-button>
        </x-page-header>
    @endroute

    <x-table :total="$this->users->total()" :links="$this->users->links()">
        @if ($account)
            <x-slot:header>
                <div class="flex items-center gap-4 justify-between">
                    <div>{{ _('Users') }}</div>
                    <x-button size="sm" icon="plus" :href="route('app.user.create', ['account' => $account->id])">
                        {{ _('New User') }}
                    </x-button>
                </div>
            </x-slot:header>
        @endif

        <x-slot:head>
            <x-table head sort="name">{{ __('Name') }}</x-table>
            
            @if (auth()->user()->isAccountType('root'))
                <x-table head>{{ __('Type') }}</x-table>
            @endif
            
            @module('roles')
                <x-table head align="right">{{ __('Role') }}</x-table>
            @endmodule

            <x-table head/>
        </x-slot:head>

        <x-slot:body>
            @foreach ($this->users as $user)
                <x-table row>
                    <x-table cell>
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
                    </x-table>

                    @if (auth()->user()->isAccountType('root'))
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
        </x-slot:body>

        <x-slot:empty>
            <x-empty-state :title="__('No Users')" :subtitle="__('User list is empty')"/>
        </x-slot:empty>
    </x-table>
</div>