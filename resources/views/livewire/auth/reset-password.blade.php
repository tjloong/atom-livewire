<div class="flex flex-col gap-4">
    <x-form>
        <x-form.group>
            <x-heading title="auth.heading.reset-password" 2xl/>
    
            <x-form.field label="auth.label.email" :value="data_get($inputs, 'email')"/>
    
            <x-form.password label="auth.label.password" autofocus
                wire:model.defer="inputs.password"/>
    
            <x-form.password label="auth.label.password-confirm"
                wire:model.defer="inputs.password_confirmation"/>
        </x-form.group>

        <x-slot:foot>
            <x-button.submit block label="auth.button.reset-password"/>
        </x-slot:foot>
    </x-form>

    <a href="{{ route('login') }}" class="flex items-center gap-2">
        <x-icon name="arrow-left"></x-icon> {{ tr('common.label.back-to-login') }}
    </a>
</div>
