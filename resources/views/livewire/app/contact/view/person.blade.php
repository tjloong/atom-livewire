<div class="max-w-screen-lg mx-auto w-full">
    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->persons->total()"/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Name" sort="name"/>
            <x-table.th label="Created Date" sort="created_at" class="text-right"/>
        </x-slot:thead>
    
        @foreach ($this->persons as $person)
            <x-table.tr>
                <x-table.td>
                    <a wire:click="$emitTo('{{ lw('app.contact.form.person-modal') }}', 'open', @js($person->id))">
                        {{ $person->name }}
                    </a>
                    @if ($email = $person->email)
                        <div class="text-sm font-medium text-gray-500">{{ $email }}</div>
                    @endif
                </x-table.td>
    
                <x-table.td :date="$person->created_at" class="text-right"/>
            </x-table.tr>
        @endforeach
    
        <x-slot:empty>
            <x-empty-state title="No Contact Person" subtitle="This contact person list is empty.">
                <x-button color="gray"
                    label="New Contact Person"
                    wire:click="$emitTo('{{ lw('app.contact.form.person-modal') }}', 'open')"
                />
            </x-empty-state>
        </x-slot:empty>
    </x-table>

    {!! $this->persons->links() !!}

    @livewire(lw('app.contact.form.person-modal'), compact('contact'), key('person-modal'))
</div>

