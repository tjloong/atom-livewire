<x-form heading="app.label.change-password" icon="lock">
    <x-group cols="2" class="p-5">
        <x-input type="password" wire:model.defer="password.current" label="Current Password"/>
        <div></div>
        <x-input type="password" wire:model.defer="password.new" label="New Password"/>
        <x-input type="password" wire:model.defer="password.new_confirmation" label="Confirm New Password"/>
    </x-group>
</x-form>
