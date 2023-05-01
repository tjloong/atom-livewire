<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Invitations">
        <x-button label="Invite User" :href="route('app.invitation.create')"/>
    </x-page-header>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>