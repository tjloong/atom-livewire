<div class="flex flex-col gap-4">
    <x-form x-recaptcha:submit.reset-password.prevent="() => $wire.submit()">
        <x-inputs>
            <x-heading title="app.label.reset-password" xl/>
    
            <x-form.field label="app.label.email" :value="data_get($inputs, 'email')"/>
    
            <x-form.password label="app.label.password" autofocus
                wire:model.defer="inputs.password"/>
    
            <x-form.password label="app.label.confirm-password"
                wire:model.defer="inputs.password_confirmation"/>
        </x-inputs>

        <x-slot:foot>
            <x-button action="submit" block label="app.label.reset-password"/>
        </x-slot:foot>
    </x-form>

    <x-anchor label="app.label.back-to-login" icon="back" href="/login"/>
</div>
