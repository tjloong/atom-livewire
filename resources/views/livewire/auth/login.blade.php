<div class="flex flex-col gap-4">
    <x-form submit="login">
        <x-form.group>
            <div class="text-2xl font-bold">
                {{ __('Sign in to your account') }}
            </div>

            @if ($flash = session('flash')) <x-alert>{{ __($flash) }}</x-alert> @endif

            <x-form.email wire:model.defer="email" required autofocus/>

            <div>
                <x-form.password wire:model.defer="password" required/>
    
                @if (Route::has('password.forgot'))
                    <a href="{{ route('password.forgot') }}" class="text-theme font-medium text-sm">
                        {{ __('Forgot Password?') }}
                    </a>
                @endif
            </div>
        </x-form.group>
        
        <x-slot:foot>
            <x-button.submit label="Login" size="md" color="theme" block/>
            <x-button.social-login size="md" divider="OR LOGIN WITH"/>
        </x-slot:foot>
    </x-form>

    @if (Route::has('register'))
        <div class="inline-flex item-center gap-2 px-4">
            {{ __('Don\'t have an account?') }} 
    
            <a href="{{ route('register', ['ref' => 'page-login']) }}" class="inline-flex items-center gap-2">
                {{ __('Sign Up') }} <x-icon name="arrow-right"/>
            </a>
        </div>
    @endif
</div>
