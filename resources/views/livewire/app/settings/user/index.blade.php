<div class="max-w-screen-md">
    <x-heading title="Users">
        <x-button icon="add" 
            label="New User" 
            wire:click="$emit('createUser')"/>
    </x-heading>
    @livewire('app.settings.user.listing', key('listing'))
</div>