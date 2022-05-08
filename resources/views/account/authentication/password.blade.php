<x-form>
    <x-slot:header>{{ __('Change Password') }}</x-slot:header>

    <x-form.password 
        label="Current Password"
        wire:model.defer="password.current" 
        :error="$errors->first('password.current')" 
        required
    />

    <div class="grid gap-4 md:grid-cols-2">
        <x-form.password 
            label="New Password"
            wire:model.defer="password.new" 
            :error="$errors->first('password.new')" 
            required
        />

        <x-form.password 
            label="Confirm New Password"
            wire:model.defer="password.new_confirmation" 
            :error="$errors->first('password.new_confirmation')" 
            required
        />
    </div>

    <x-slot:foot>
        <x-button.submit label="Change Password"/>
    </x-slot:foot>
</x-form>
