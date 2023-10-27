<div class="flex flex-col gap-4">
    <x-form recaptcha="login">
        <x-form.group>
            <x-heading title="auth.heading.login" 2xl/>

            @if ($message = session('message')) <x-alert>{{ $message }}</x-alert> @endif
            @if ($errors->has('email')) <x-alert.errors/> @endif

            <x-form.text :label="$this->usernameLabel" autofocus
                wire:model.defer="inputs.username"/>
    
            <div>
                <x-form.password label="auth.label.password"
                    wire:model.defer="inputs.password"/>
    
                @if (has_route('password.forgot'))
                    <x-link label="auth.button.forgot-password"
                        :href="route('password.forgot')" 
                        class="text-theme text-sm"/>
                @endif
            </div>
        </x-form.group>
    
        <x-slot:foot>
            <x-button.submit label="auth.button.login" icon="login" color="theme" block lg/>
            <x-button.social-login divider="auth.label.or-login-with"/>
        </x-slot:foot>
    </x-form>

    @if (has_route('register'))
        <div class="inline-flex item-center gap-2 px-4">
            {{ tr('auth.label.dont-have-account') }} 
            <x-link label="auth.button.signup-now" 
                :href="route('register', ['utm' => 'page-login'])"/>
        </div>
    @endif
</div>
