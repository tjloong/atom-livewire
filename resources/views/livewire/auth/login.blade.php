<div class="flex flex-col gap-4">
    <x-form>
        <x-form.group>
            <div class="text-2xl font-bold">
                {{ __('Sign in to your account') }}
            </div>

            @if ($flash = session('flash')) <x-alert>{{ __($flash) }}</x-alert> @endif

            <x-alert.errors/>

            <x-form.text wire:model.defer="inputs.username" 
                :label="$this->usernameLabel"
                required 
                autofocus
            />

            <div>
                <x-form.password wire:model.defer="inputs.password" required/>
    
                @if (has_route('password.forgot'))
                    <x-link label="Forgot Password?" :href="route('password.forgot')" class="text-theme text-sm"/>
                @endif
            </div>
        </x-form.group>
        
        <x-slot:foot>
            <x-button.submit label="Login" icon="login" size="md" color="theme" block/>
            <x-button.social-login size="md" divider="OR LOGIN WITH"/>
        </x-slot:foot>
    </x-form>

    @if (has_route('register'))
        <div class="inline-flex item-center gap-2 px-4">
            {{ __('Don\'t have an account?') }} 
            <x-link label="Sign Up Now" :href="route('register', ['ref' => 'page-login'])"/>
        </div>
    @endif
</div>
