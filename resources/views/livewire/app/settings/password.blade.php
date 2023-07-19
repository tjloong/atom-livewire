<div class="max-w-screen-md">
    <x-page-header title="Change Password"/>
    
    <x-form>
        <x-form.group cols="2">
            <x-form.password wire:model.defer="password.current" label="Current Password"/>
        </x-form.group>
    
        <x-form.group cols="2">
            <x-form.password wire:model.defer="password.new" label="New Password"/>
            <x-form.password wire:model.defer="password.new_confirmation" label="Confirm New Password"/>
        </x-form.group>
    </x-form>
</div>
