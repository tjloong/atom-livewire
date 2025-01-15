<div class="max-w-screen-md">
    <atom:_table :paginate="$this->users" :trashed="$this->trashed">
        <x-slot:total>
            <atom:_heading size="xl">
                @t('users')
            </atom:_heading>
        </x-slot:total>

        <x-slot:bar>
            <atom:_button icon="add" size="sm" x-on:click="Atom.modal('atom.user').slide()">
                @t('new-user')
            </atom:_button>
        </x-slot:bar>

        <x-slot:filters>
            <atom:_select wire:model="filters.status" options="enum.user-status"/>
        </x-slot:filters>

        <atom:columns>
            <atom:column sort="name">@t('name')</atom:column>
            <atom:column>@t('email')</atom:column>
            <atom:column/>
        </atom:columns>

        <atom:rows>
            @foreach ($this->users as $row)
                <atom:row x-on:click="Atom.modal('atom.user.edit').slide({ id: {{js($row->id)}} })">
                    <atom:cell class="font-medium">@e($row->name)</atom:cell>
                    <atom:cell>@e($row->email)</atom:cell>
                    <atom:cell align="right">
                        <atom:_badge :status="$row->status"/>
                    </atom:cell>
                </atom:row>
            @endforeach
        </atom:rows>
    </atom:_table>

    <livewire:atom.user.edit :wire:key="$this->wirekey('atom.user.edit')"/>
</div>
