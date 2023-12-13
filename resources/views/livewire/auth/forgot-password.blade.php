<div class="flex flex-col gap-4">
    <x-form recaptcha="forgot_password">
        <x-form.group>
            <div class="text-2xl font-bold">
                {{ tr('auth.label.send-reset-password-request') }}
            </div>
    
            <x-form.email wire:model.defer="email" label="app.label.login-email"/>
        </x-form.group>

        <x-slot:foot>
            <x-button.submit block icon="send" label="app.label.send-request"/>
        </x-slot:foot>
    </x-form>
    
    <x-link icon="back" label="app.label.back-to-login" :href="route('login')"/>
</div>