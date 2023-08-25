<div>
    <x-button.social-login
        size="md"
        divider="OR"
        divider-position="bottom"
    />

    <div class="flex flex-col gap-4">
        <x-form class="p-5">
            <x-form.group>
                <div class="text-2xl font-bold text-gray-600">
                    {{ __('Create your account') }}
                </div>
            
                <x-form.text wire:model.defer="inputs.name" label="Your Name" autofocus/>
                <x-form.email wire:model.defer="inputs.email" label="Login Email"/>
                <x-form.password wire:model.defer="inputs.password" label="Login Password"/>
            
                <div class="grid gap-2">
                    <x-form.checkbox.privacy wire:model="inputs.agree_tnc"/>
                    <x-form.checkbox.marketing wire:model="inputs.agree_promo"/>
                </div>
            </x-form.group>
    
            <x-slot:foot>
                <x-button.submit size="md" label="Create Account" block/>
            </x-slot:foot>
        </x-form>
        
        <div class="text-center">
            {{ __('Have an account?') }} <x-link label="Sign In" :href="route('login')"/>
        </div>
    </div>
</div>

