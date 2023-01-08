<div class="flex flex-col gap-4">
    <x-form wire:submit.prevent="login">
        <div class="text-2xl font-bold">
            {{ __('Sign in to your account') }}
        </div>

        @if (session('flash'))
            <x-alert>{{ __(session('flash')) }}</x-alert>
        @endif

        @if ($errors->any())
            <x-alert :errors="$errors->all()"/>
        @endif

        <x-form.email label="Email"
            wire:model.defer="email"
            required
            autofocus
        />

        <div>
            <x-form.password label="Password"
                wire:model.defer="password"
                required
            />

            @if (Route::has('password.forgot'))
                <a href="{{ route('password.forgot') }}" class="text-theme font-medium text-sm">
                    {{ __('Forgot Password?') }}
                </a>
            @endif
        </div>

        <x-slot:foot>
            <x-button type="submit" size="md" block
                label="Login"
            />

            <x-button.social-login 
                size="md"
                divider="OR LOGIN WITH"
            />
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
