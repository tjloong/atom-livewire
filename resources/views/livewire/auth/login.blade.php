<div class="max-w-md mx-auto grid gap-10">
    <a class="mx-auto" href="/">
        <x-logo class="w-40"/>
    </a>

    <form wire:submit.prevent="login" class="grid gap-6">
        <x-box>
            <div class="grid gap-6 p-5 md:p-10">
                <div class="text-2xl font-bold">
                    {{ __('Sign in to your account') }}
                </div>

                @if (session('flash'))
                    <div class="bg-blue-100 text-blue-800 rounded p-4">
                        {{ session('flash') }}
                    </div>
                @endif

                @if ($errors->any())
                    <x-alert :errors="$errors->all()"/>
                @endif

                <div class="grid gap-4">
                    <div>
                        <div class="font-medium text-gray-500 text-sm uppercase mb-2">
                            {{ __('Email') }}
                        </div>
                        <input type="email" wire:model.defer="email" class="w-full form-input" tabindex="1" required autofocus>
                    </div>
    
                    <div>
                        <div class="flex justify-between mb-2">
                            <div class="font-medium text-gray-500 text-sm uppercase">
                                {{ __('Password') }}
                            </div>
    
                            @if (Route::has('password.forgot'))
                                <a href="{{ route('password.forgot') }}" class="text-theme font-medium text-sm">
                                    {{ __('Forgot Password?') }}
                                </a>
                            @endif
                        </div>
                        <input type="password" wire:model.defer="password" class="w-full form-input" tabindex="2" required>
                    </div>
                </div>

                <x-button type="submit" size="md" wire:loading.class="loading">
                    {{ __('Login') }}
                </x-button>
            </div>
        </x-box>

        @if (Route::has('register'))
            <div>
                {{ __('Don\'t have an account?') }} 
        
                <a href="{{ route('register', ['ref' => 'page-login']) }}" class="inline-flex items-center">
                    {{ __('Sign Up') }} <x-icon name="right-arrow-alt" size="18px"/>
                </a>
            </div>
        @endif
    </form>
</div>
