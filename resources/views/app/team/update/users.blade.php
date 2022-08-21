<x-table :total="$this->users->total()" :links="$this->users->links()" :search="false">
    <x-slot:header>
        <div class="flex items-center justify-between gap-4">
            {{ __('Team Users') }}

            <div 
                x-data="{
                    show: false,
                    select (id) {
                        this.$wire.join(id).then(() => this.show = false)
                    },
                }"
                x-on:click.away="show = false"
                class="relative"
            >
                <div>
                    <x-button icon="plus" size="sm"
                        label="Assign User"
                        x-on:click="show = true"
                    />
                </div>

                <div 
                    x-show="show"
                    x-transition.opacity
                    class="absolute z-20 bg-white rounded-md shadow border w-[350px] overflow-auto right-0 grid divide-y"
                >
                    <div class="flex items-center gap-2 py-2 px-4 bg-gray-100">
                        <x-icon name="search" class="shrink-0 text-gray-400" size="18px"/>
                        <input type="text"
                            wire:model.debounce.400ms="search"
                            class="grow form-input transparent text-base font-normal"
                            placeholder="Search User"
                        >
                    </div>

                    <div class="max-h-[300px]">
                        <div class="grid divide-y">
                            @forelse ($this->options as $opt)
                                <a
                                    x-on:click="select({{ $opt->id }})"
                                    class="py-2 px-4 text-base flex gap-2 hover:bg-gray-100"
                                >
                                    <div class="grow">
                                        <div>{{ $opt->name }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $opt->email }}
                                        </div>
                                    </div>
                                    <div class="shrink-0">
                                        <x-badge :label="$opt->status"/>
                                    </div>
                                </a>
                            @empty
                                <x-empty-state title="No users found" subtitle="Please try other search criteria">
                                    <x-button.create label="Create User" :href="route('app.user.create')"/>
                                </x-empty-state>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:header>

    <x-slot:head>
        <x-table.th label="User" sort="name"/>
        @module('roles') <x-table.th label="Role"/> @endmodule
        <x-table.th/>
    </x-slot:head>

    <x-slot:body>
        <x-table.tr>
            @foreach ($this->users as $user)
                <x-table.td 
                    :label="$user->name" 
                    :href="route('app.user.update', [$user->id])"
                    :small="$user->email"
                />

                @module('roles')
                    <x-table.td :label="data_get($user->role, 'name')"/>
                @endmodule
                
                <x-table.td class="text-right">
                    <x-button.confirm icon="circle-minus" color="red" inverted size="xs"
                        label="Remove"
                        title="Remove User"
                        message="Remove user from team?"
                        callback="leave"
                        :params="$user->id"
                    />
                </x-table.td>
            @endforeach
        </x-table.tr>
    </x-slot:body>

    <x-slot:empty>
        <x-empty-state title="No Users" subtitle="No users assigned to this team"/>
    </x-slot:empty>
</x-table>
