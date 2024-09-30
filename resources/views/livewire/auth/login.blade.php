<div class="space-y-6">
    @if ($this->socialLogins->count())
        <div class="space-y-3">
            @foreach ($this->socialLogins as $item)
                <atom:_button variant="default" :social="$item" block>
                    @t('continue-with-social-login', ['provider' => get($item, 'label')])
                </atom:_button>
            @endforeach
        </div>

        <atom:separator>OR</atom:separator>
    @endif

    <atom:card>
        <atom:_form x-recaptcha:submit.login.prevent="() => $wire.submit()">
            <atom:_heading size="20" level="2">@t('signin')</atom:_heading>

            @if ($errors->first('failed'))
                <x-inform :message="$errors->first('failed')" type="error"/>
            @elseif ($message = session('message'))
                <x-inform :message="$message"/>
            @endif

            <x-input type="email" wire:model.defer="inputs.email" label="app.label.email" autofocus/>

            <div class="space-y-3">
                <x-input type="password" wire:model.defer="inputs.password" label="app.label.password"/>

                @if (app('route')->has('password.forgot'))
                    <x-anchor label="app.label.forgot-password" :href="route('password.forgot')" class="text-sm"/>
                @endif
            </div>

            <atom:_button action="submit" variant="primary" icon="login" block>@t('login')</atom:_button>
        </atom:_form>
    </atom:card>

    @if (app('route')->has('register'))
        <div class="text-center">
            @t('dont-have-account')
            <x-anchor label="app.label.signup-now" :href="route('register', ['utm_source' => 'page-login'])"/>
        </div>
    @endif
</div>
