<x-form header="Login Information">
    <x-form.text 
        label="Login Name"
        wire:model.defer="user.name" 
        :error="$errors->first('user.name')" 
        required
    />

    <x-form.email 
        label="Login Email"
        wire:model.defer="user.email" 
        :error="$errors->first('user.email')" 
        required
    />

    @module('roles')
        @if ($role = optional($user->role)->name)
            <x-form.field label="Role">
                {{ $role }}
            </x-form.field>
        @endif
    @endmodule

    <x-slot:foot>
        <x-button.submit label="Update Login Information"/>
    </x-slot:foot>
</x-form>
