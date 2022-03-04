<div class="max-w-md mx-auto grid gap-10">
    <a class="w-40 mx-auto" href="/">
        <x-atom-logo/>
    </a>

    <form wire:submit.prevent="login" class="grid gap-6">
        <x-box>
            <div class="grid gap-6 p-5 md:p-10">
                <div class="text-2xl font-bold">
                    Sign in to your account
                </div>

                @if (session('flash'))
                    <div class="text-sm bg-blue-100 text-blue-800 rounded p-4">
                        {{ session('flash') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 text-red-800 rounded p-4 grid gap-2">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-1 text-sm">
                                <x-icon name="x"/> {{ $error }}
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="grid gap-4">
                    <div>
                        <div class="font-medium text-gray-500 text-xs uppercase mb-2">Email</div>
                        <input type="text" wire:model.defer="email" class="w-full form-input" tabindex="1" required autofocus>
                    </div>
    
                    <div>
                        <div class="flex justify-between mb-2">
                            <div class="font-medium text-gray-500 text-xs uppercase">
                                Password
                            </div>
    
                            @if (Route::has('password.forgot'))
                                <a href="{{ route('password.forgot') }}" class="text-theme font-medium text-xs">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>
                        <input type="password" wire:model.defer="password" class="w-full form-input" tabindex="2" required>
                    </div>
                </div>

                <x-button type="submit" size="md" wire:loading.class="loading">
                    Login
                </x-button>
            </div>
        </x-box>

        @if (Route::has('register'))
            <div class="text-sm">
                Don't have an account? 
        
                <a href="{{ route('register', ['ref' => 'page-login']) }}" class="inline-flex items-center">
                    Sign Up <x-icon name="right-arrow-alt" size="18px"/>
                </a>
            </div>
        @endif
    </form>
</div>
