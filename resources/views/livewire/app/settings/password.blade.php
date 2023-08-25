<div class="max-w-screen-md">
    <x-heading title="Change Password" 2xl/>
    
    <x-form>
        <x-form.group cols="2" class="p-5">
            <x-form.password wire:model.defer="password.current" label="Current Password"/>
            <div></div>
            <x-form.password wire:model.defer="password.new" label="New Password"/>
            <x-form.password wire:model.defer="password.new_confirmation" label="Confirm New Password"/>
        </x-form.group>
    </x-form>
</div>
