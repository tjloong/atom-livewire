<div class="max-w-md mx-auto flex flex-col items-center">
    <a class="w-40 mb-10" href="/">
        <img src="/storage/img/logo.svg" class="w-full">
    </a>

    <form wire:submit.prevent="login" class="w-full">
        <x-box>
            <div class="p-5 md:p-10">
                <div class="text-2xl font-bold text-gray-700 mb-6">
                    Sign in to your account
                </div>

                @if (session('flash'))
                    <div class="mb-4 text-sm bg-blue-100 text-blue-800 rounded p-4">
                        {{ session('flash') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 text-sm bg-red-100 text-red-800 rounded p-4">
                        @foreach ($errors->all() as $error)
                        <div class="flex">
                            <x-icon name="x" />
                            <div class="leading-relaxed">
                                {{ $error }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                <div class="mb-5 w-full">
                    <div class="font-medium text-gray-500 text-xs uppercase mb-2">Email</div>
                    <input type="text" wire:model.defer="email" class="w-full form-input" tabindex="1" required autofocus>
                </div>

                <div class="mb-8 w-full">
                    <div class="flex justify-between mb-2">
                        <div class="font-medium text-gray-500 text-xs uppercase">
                            Password
                        </div>

                        <a href="{{ route('password.forgot') }}" class="text-theme font-medium text-xs">
                            Forgot Password?
                        </a>
                    </div>
                    <input type="password" wire:model.defer="password" class="w-full form-input" tabindex="2" required>
                </div>

                <x-button type="submit" size="md" class="w-full" wire:loading.class="loading">
                    Login
                </x-button>
            </div>
        </x-box>
    </form>

    <div class="text-sm">
        Don't have an account? 

        <a href="{{ route('register', ['ref' => 'page-login']) }}" class="inline-flex items-center">
            Sign Up <x-icon name="right-arrow-alt" size="18px"/>
        </a>
    </div>
</div>