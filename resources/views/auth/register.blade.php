<div class="max-w-md mx-auto grid gap-10">
    <a class="mx-auto" href="/">
        <x-logo class="w-40"/>
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
                            <x-input.text wire:model.defer="form.name" :error="$errors->first('form.name')" required>
                                {{ __('Your Name') }}
                            </x-input.text>

                            <x-input.email wire:model.defer="form.email" :error="$errors->first('form.email')" required>
                                {{ __('Login Email') }}
                            </x-input.email>
        
                            <x-input.password wire:model.defer="form.password" :error="$errors->first('form.password')" required>
                                {{ __('Login Password') }}
                            </x-input.password>
                        </div>
        
                        <div class="grid gap-4">
                            <div>
                                <x-input.agree wire:model="form.agree_tnc" tnc/>
                            </div>
        
                            <div>
                                <x-input.agree wire:model="form.agree_marketing" marketing/>
                            </div>
                        </div>
                    </div>
        
                    @if ($errors->has('form.agree_tnc'))
                        <x-alert type="error">{{ $errors->first('form.agree_tnc') }}</x-alert>
                    @endif

                    <x-button type="submit" size="md">
                        {{ __('Create Account') }}
                    </x-button>
                </div>
            </x-box>
        </form>
        
        <div class="text-center">
            {{ __('Have an account?') }}
            <a href="{{ route('login') }}">
                {{ __('Sign In') }}
            </a>
        </div>
    </div>
</div>
