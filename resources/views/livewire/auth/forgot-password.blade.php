<div class="flex flex-col gap-4">
    <x-form class="p-5">
        <x-form.group>
            <div class="text-2xl font-bold">
                {{ __('Reset Password Request') }}
            </div>
    
            <x-form.email 
                label="Your registered email"
                wire:model.defer="email"
                required
                autofocus
            />
        </x-form.group>

        <x-slot:foot>
            <x-button.submit size="md" block
                label="Send Request"
            />
        </x-slot:foot>
    </x-form>
    
    <a href="{{ route('login') }}" class="flex items-center gap-2">
        <x-icon name="arrow-left"></x-icon> {{ __('Back to login') }}
    </a>
</div>