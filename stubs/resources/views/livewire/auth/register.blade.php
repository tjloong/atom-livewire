<div class="max-w-md mx-auto">
    @if ($isCompleted)
        <div class="flex flex-col space-y-4">
            <a href="/">
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
    
            <div>
                <a class="py-2 px-4 bg-theme text-white inline-flex items-center rounded-md shadow" href="{{ route('dashboard') }}">
                    Go to Dashboard <x-icon name="chevron-right"/>
                </a>
            </div>
        </div>
    @else
        <div class="flex flex-col items-center">
            <a class="w-40 mb-10" href="/">
                <x-atom-logo/>
            </a>
        
            <form class="mb-6" wire:submit.prevent="register">
                <x-box>
                    <div class="p-5 md:p-10">
                        <div class="text-2xl font-bold mb-8 text-gray-600">
                            Create your account
                        </div>
        
                        <x-input.text wire:model.defer="name" required>
                            Your Full Name
                        </x-input.text>
        
                        <x-input.email wire:model.defer="email" required>
                            Login Email
                        </x-input.email>
        
                        <x-input.password wire:model.defer="password" required>
                            Login Password
                        </x-input.password>
        
                        <div class="space-y-4 my-6">
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
        
                            <x-input.checkbox wire:model.defer="agreeMarketing">
                                <div class="text-gray-500">
                                    I agree to be part of the app's database for future newsletter, marketing and promotions opportunities.
                                </div>
                            </x-input.checkbox>
                        </div>
        
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
        
                        <x-button type="submit" size="md" wire:loading.class="loading" class="w-full mb-4">
                            Create Account
                        </x-button>
        
                        <div class="text-center text-sm">
                            Have an account? 
                            <a href="{{ route('login') }}">
                                Sign In
                            </a>
                        </div>
                    </div>
                </x-box>
            </form>        
        </div>
    @endif
</div>