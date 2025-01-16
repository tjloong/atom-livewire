<div class="space-y-6">
    <atom:social-logins separator-bottom="OR"/>

    <atom:card>
        <atom:_form x-recaptcha:submit.login.prevent="() => $wire.submit()">
            <atom:_heading size="20" level="2">@t('signin')</atom:_heading>

            @if ($errors->first('failed'))
                <x-inform variant="danger">@e($errors->first('failed'))</x-inform>
            @elseif ($message = session('message'))
                <x-inform>@e($message)</x-inform>
            @endif

            <atom:_input type="email" wire:model.defer="inputs.email" label="email" autofocus/>

            <div class="space-y-3">
                <atom:_input type="password" wire:model.defer="inputs.password" label="password"/>

                @if (app('route')->has('password.forgot'))
                    <atom:link :href="route('password.forgot')" class="text-sm">@t('forgot-password')?</atom:link>
                @endif
            </div>

            <atom:_button action="submit" variant="primary" icon="login" block>@t('login')</atom:_button>
        </atom:_form>
    </atom:card>

    @if (app('route')->has('register'))
        <div class="text-center">
            @t('dont-have-account')
            <atom:link :href="route('register', ['utm_source' => 'page-login'])">@t('signup-now')</atom:link>
        </div>
    @endif
</div>
