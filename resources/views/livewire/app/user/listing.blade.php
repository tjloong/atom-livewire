<atom:_table :paginate="$this->users" :trashed="$this->trashed">
    @slot ('filters')
        <x-select wire:model="filters.status" options="enum.user-status"/>
    @endslot

    <atom:columns>
        <atom:column sort="name">@t('name')</atom:column>
        <atom:column>@t('email')</atom:column>
        <atom:column/>
    </atom:columns>

    <atom:rows>
        @foreach ($this->users as $row)
            <atom:row x-on:click="Atom.modal('edit-user').show({ id: {{js($row->id)}} })">
                <atom:cell class="font-medium">@e($row->name)</atom:cell>
                <atom:cell>@e($row->email)</atom:cell>
                <atom:cell align="right">
                    <atom:_badge :status="$row->status"/>
                </atom:cell>
            </atom:row>
        @endforeach
    </atom:rows>
</atom:_table>
