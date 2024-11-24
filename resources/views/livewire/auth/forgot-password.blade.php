<div class="space-y-6">
    <atom:card>
        <atom:_form x-recaptcha:submit.forgot_password.prevent="() => $wire.submit()">
            <atom:_heading size="xl">
                @t('send-reset-password-request')
            </atom:_heading>

            <atom:_input type="email" wire:model.defer="email" label="login-email"/>

            <atom:_button action="submit" block>@t('send-request')</atom:_button>
        </atom:_form>
    </atom:card>

    <atom:link icon="back" :href="route('login')">@t('back-to-login')</atom:link>
</div>
