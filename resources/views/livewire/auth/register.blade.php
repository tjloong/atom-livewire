<div class="flex flex-col gap-4">
    <x-form>
        <div class="text-2xl font-bold text-gray-600">
            Create your account
        </div>
    
        <x-form.text label="Your Name"
            wire:model.defer="form.name" 
            :error="$errors->first('form.name')" 
            required
        />

        <x-form.email label="Login Email"
            wire:model.defer="form.email" 
            :error="$errors->first('form.email')" 
            required
        />

        <x-form.password label="Login Password"
            wire:model.defer="form.password" 
            :error="$errors->first('form.password')" 
            required
        />
    
        <div class="grid gap-2">
            <x-form.agree tnc wire:model="form.agree_tnc"/>
            <x-form.agree marketing wire:model="form.agree_marketing"/>
        </div>

        @if ($errors->has('form.agree_tnc'))
            <x-alert type="error">{{ $errors->first('form.agree_tnc') }}</x-alert>
        @endif

        <x-slot:foot>
            <x-button.submit size="md" block
                label="Create Account"
            />
        </x-slot:foot>
    </x-form>
    
    <div class="text-center">
        {{ __('Have an account?') }}
        <a href="{{ route('login') }}">
            {{ __('Sign In') }}
        </a>
    </div>
</div>
