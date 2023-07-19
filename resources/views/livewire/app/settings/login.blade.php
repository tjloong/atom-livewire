<div class="max-w-screen-md">
    <x-page-header title="Login Information"/>

    <x-form>
        <x-form.group cols="2">
            <x-form.text wire:model.defer="user.name" label="Login Name"/>
            <x-form.email wire:model.defer="user.email" label="Login Email"/>
    
            @if ($role = optional($user->role)->name)
                <x-form.text :value="$role" label="Role" readonly/>
            @endif
        </x-form.group>
    </x-form>
</div>
