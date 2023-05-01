<x-form>
    <x-form.group cols="2">
        @if ($user->exists)
            <x-form.text label="Login Name" :value="$user->name" readonly/>
            <x-form.text label="Login Email" :value="$user->email" readonly/>
        @else
            <x-form.text label="Login Name" wire:model.defer="user.name"/>
            <x-form.email label="Login Email" wire:model.defer="user.email"/>
        @endif

        @module('roles')
            <x-form.select label="Role" wire:model="user.role_id" :options="data_get($this->options, 'roles')"/>
        @endmodule

        @module('teams')
            <x-form.select label="Teams" wire:model="teams" :options="data_get($this->options, 'teams')" multiple/>
        @endmodule

        <x-form.select label="Data Visibility" wire:model="user.visibility" :options="data_get($this->options, 'visibilities')"/>
    </x-form.group>
</x-form>
