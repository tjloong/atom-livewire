<x-form>
    <x-slot:header>{{ __('Change Login Information') }}</x-slot:header>

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

    <x-slot:foot>
        <x-button.submit label="Update Profile"/>
    </x-slot:foot>
</x-form>
