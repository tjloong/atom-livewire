<div class="flex flex-col gap-4">
    <x-form recaptcha="forgot_password">
        <x-form.group>
            <div class="text-2xl font-bold">
                {{ tr('auth.heading.forgot-password') }}
            </div>
    
            <x-form.email label="auth.label.login-email"
                wire:model.defer="email"/>
        </x-form.group>

        <x-slot:foot>
            <x-button.submit block
                label="auth.button.send-request"/>
        </x-slot:foot>
    </x-form>
    
    <x-link icon="back" label="common.label.back-to-login"
        :href="route('login')"/>
</div>