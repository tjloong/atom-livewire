<div class="max-w-screen-md">
    <x-heading title="Users" lg>
        <x-button icon="add" label="New User" wire:click="$emit('createUser')"/>
    </x-heading>
    @livewire('app.user.listing', key('listing'))
</div>