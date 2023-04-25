<div class="w-full">
    <x-page-header title="Login Information"/>

    <x-form>
        <x-form.group cols="2">
            <x-form.text wire:model.defer="user.name" label="Login Name"/>
            <x-form.email wire:model.defer="user.email" label="Login Email"/>
    
            @module('roles')
                @if ($role = optional($user->role)->name)
                    <x-form.field label="Role">
                        {{ $role }}
                    </x-form.field>
                @endif
            @endmodule
        </x-form.group>
    </x-form>
</div>
