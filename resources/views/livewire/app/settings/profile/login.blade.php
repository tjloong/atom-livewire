<x-form heading="app.label.login-information" icon="login">
    <x-group cols="2">
        <x-form.text wire:model.defer="user.name" label="Login Name"/>
        <x-form.email wire:model.defer="user.email" label="Login Email"/>
    </x-group>
</x-form>
