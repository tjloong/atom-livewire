<div class="flex flex-col gap-4">
    <x-form recaptcha="login">
        <x-group>
            <x-heading title="auth.label.signin" 2xl/>

            @if ($message = session('message')) <x-alert>{{ $message }}</x-alert> @endif
            @if ($errors->has('email')) <x-alert.errors/> @endif

            <x-form.text wire:model.defer="inputs.email" label="app.label.email" autofocus/>

            <div class="flex flex-col gap-2">
                <x-form.password wire:model.defer="inputs.password" label="app.label.password"/>
    
                @if (has_route('password.forgot'))
                    <x-link label="auth.label.forgot-password" :href="route('password.forgot')" class="text-theme text-sm"/>
                @endif
            </div>
        </x-group>
    
        <x-slot:foot>
            <x-button.submit label="app.label.login" icon="login" color="theme" block lg/>
            
            @if (model('setting')->getSocialLogins()->count())
                <x-divider label="or"/>
                <x-button.social-login/>
            @endif
        </x-slot:foot>
    </x-form>

    @if (has_route('register'))
        <div class="inline-flex item-center gap-2 px-4">
            {{ tr('auth.label.dont-have-account') }} 
            <x-link label="auth.label.signup-now" :href="route('register', ['utm' => 'page-login'])"/>
        </div>
    @endif
</div>
