<div>
    @if ($isCompleted)
        <div class="max-w-lg mx-auto grid gap-8">
            <a href="/" class="w-40">
                <x-atom-logo/>
            </a>

            <div>
                <div class="text-3xl font-bold mb-2">
                    Thank you for signing up with us
                </div>

                <p>
                    Your account is successfully created. We are so excited to have you as our newest friend!
                </p>
            </div>

            @if (Route::has('dashboard'))
                <div>
                    <a class="py-2 px-4 bg-theme text-white inline-flex items-center rounded-md shadow" href="{{ route('dashboard') }}">
                        Go to Dashboard <x-icon name="chevron-right"/>
                    </a>
                </div>
            @endif
        </div>
    @else
        <div class="max-w-md mx-auto grid gap-10">
        <a class="w-40 mx-auto" href="/">
            <x-atom-logo/>
        </a>

        <form wire:submit.prevent="register">
            <x-box>
                <div class="grid gap-8 p-5 md:p-10">
                    <div class="text-2xl font-bold text-gray-600">
                        Create your account
                    </div>

                    <div>
                        <x-input.text wire:model.defer="name" required>
                            Your Full Name
                        </x-input.text>
    
                        <x-input.email wire:model.defer="email" required>
                            Login Email
                        </x-input.email>
    
                        <x-input.password wire:model.defer="password" required>
                            Login Password
                        </x-input.password>
                    </div>

                    <div class="grid gap-4">
                        <div>
                            <x-input.checkbox wire:model.defer="agreeTnc">
                                <div class="text-gray-500">
                                    By signing up, I have read and agreed to the app's
                                    <a href="/terms" target="_blank">
                                        terms and conditions
                                    </a> and 
                                    <a href="/privacy" target="_blank">
                                        privacy policy
                                    </a>.
                                </div>
                            </x-input.checkbox>
                        </div>

                        <div>
                            <x-input.checkbox wire:model.defer="agreeMarketing">
                                <div class="text-gray-500">
                                    I agree to be part of the app's database for future newsletter, marketing and promotions opportunities.
                                </div>
                            </x-input.checkbox>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-800 rounded p-4 grid gap-2">
                            @foreach ($errors->all() as $error)
                            <div class="flex gap-2">
                                <x-icon name="x" class="py-0.5"/>
                                <div class="text-sm">
                                    {{ $error }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="grid gap-4">
                        <x-button type="submit" size="md" wire:loading.class="loading">
                            Create Account
                        </x-button>

                        <div class="text-center text-sm">
                            Have an account? 
                            <a href="{{ route('login') }}">
                                Sign In
                            </a>
                        </div>
                    </div>
                </div>
            </x-box>
        </form>
    @endif
</div>
