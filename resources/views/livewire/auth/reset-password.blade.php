<div class="flex flex-col gap-4">
    <x-form>
        <x-form.group>
            <x-heading title="app.label.reset-password" 2xl/>
    
            <x-form.field label="app.label.email" :value="data_get($inputs, 'email')"/>
    
            <x-form.password label="app.label.password" autofocus
                wire:model.defer="inputs.password"/>
    
            <x-form.password label="app.label.confirm-password"
                wire:model.defer="inputs.password_confirmation"/>
        </x-form.group>

        <x-slot:foot>
            <x-button.submit block label="app.label.reset-password"/>
        </x-slot:foot>
    </x-form>

    <x-link label="app.label.back-to-login" icon="back" href="/login"/>
</div>
