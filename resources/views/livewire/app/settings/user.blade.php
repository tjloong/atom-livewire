<div class="max-w-screen-md">
    <x-heading title="Users" lg>
        <x-button icon="add" label="New User" wire:click="$emit('editUser')"/>
    </x-heading>
    <livewire:app.user.listing wire:key="listing"/>
</div>