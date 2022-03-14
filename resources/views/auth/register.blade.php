<div class="max-w-md mx-auto grid gap-10">
    <a class="w-40 mx-auto" href="/">
        <x-atom-logo/>
    </a>

    <div class="grid gap-6">
        <form wire:submit.prevent="submit">
            <x-box>
                <div class="grid gap-8 p-5 md:p-10">
                    <div class="text-2xl font-bold text-gray-600">
                        Create your account
                    </div>
        
                    <div class="grid gap-4">
                        <div>
                            <x-input.text wire:model.defer="form.name" required>
                                Your Name
                            </x-input.text>
        
                            <x-input.email wire:model.defer="form.email" required>
                                Login Email
                            </x-input.email>
        
                            <x-input.password wire:model.defer="form.password" required>
                                Login Password
                            </x-input.password>
                        </div>
        
                        <div class="grid gap-4 text-sm">
                            <div>
                                <x-input.checkbox wire:model="form.agree_tnc">
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
                                <x-input.checkbox wire:model="form.agree_marketing">
                                    <div class="text-gray-500">
                                        I agree to be part of the app's database for future newsletter, marketing and promotions opportunities.
                                    </div>
                                </x-input.checkbox>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 text-red-800 rounded p-4 grid gap-2">
                            @foreach ($errors->all() as $error)
                                <div class="flex items-center gap-1">
                                    <x-icon name="x"/> {{ $error }}
                                </div>
                            @endforeach
                        </div>
                    @endif
        
                    <x-button type="submit" size="md">
                        Create Account
                    </x-button>
                </div>
            </x-box>
        </form>
        
        <div class="text-center">
            Have an account? 
            <a href="{{ route('login') }}">
                Sign In
            </a>
        </div>
    </div>
</div>
