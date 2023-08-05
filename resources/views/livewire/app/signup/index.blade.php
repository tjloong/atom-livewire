<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Sign-Ups"/>
    
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()">
                <x-table.export/>
            </x-table.searchbar>

            <x-table.toolbar>
                <x-form.select.enum :label="false"
                    wire:model="filters.status"
                    enum="signup.status"
                    placeholder="All Status"
                />
            </x-table.toolbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Name"/>
            <x-table.th label="Email"/>
            <x-table.th label="Status" class="text-right"/>
            <x-table.th label="Date" class="text-right"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $item)
            <x-table.tr>
                <x-table.td :label="$item->user->name" wire:click="setSignupId({{ $item->id }})"/>
                <x-table.td :label="$item->user->email"/>
                <x-table.td :status="[$item->status->color() => $item->status->value]" class="text-right"/>
                <x-table.td :date="$item->created_at" class="text-right"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}

    <div 
        x-data="{ signupId: @js($signupId) }"
        x-init="signupId && $wire.emit('updateSignup', signupId)"
        wire:close="setSignupId"
    >
        @livewire('app.signup.update', key(uniqid()))
    </div>
</div>