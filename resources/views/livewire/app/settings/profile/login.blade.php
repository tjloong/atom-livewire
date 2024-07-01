<x-form heading="app.label.login-information" icon="login">
    <x-group cols="2">
        <x-input wire:model.defer="user.name" label="Login Name"/>
        <x-input type="email" wire:model.defer="user.email" label="Login Email"/>
    </x-group>
</x-form>
