<div class="max-w-screen-sm mx-auto">
    <x-page-header title="Create User" back="auto"/>

    <x-form>
        <x-form.group cols="2">
            <x-form.text wire:model.defer="user.name" label="Login Name"/>
            <x-form.email wire:model.defer="user.email" label="Login Email"/>
        </x-form.group>
    
        <x-slot:error-alert></x-slot:error-alert>
    </x-form>
</div>