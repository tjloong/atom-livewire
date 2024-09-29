<div class="space-y-6">
    <atom:card>
        <atom:_form x-recaptcha:submit.login.prevent="() => $wire.submit()">
            <atom:_heading size="18" level="2">@t('app.label.signin')</atom:_heading>

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

            <x-button action="submit" label="app.label.login" icon="login" color="theme" block lg/>

            @if (model('setting')->getSocialLogins()->count())
                <atom:separator>OR</atom:separator>
                <x-button-social lg/>
            @endif
        </atom:_form>
    </atom:card>

    @if (app('route')->has('register'))
        <div class="inline-flex item-center gap-2 px-4">
            @t('app.label.dont-have-account')
            <x-anchor label="app.label.signup-now" :href="route('register', ['utm_source' => 'page-login'])"/>
        </div>
    @endif
</div>
