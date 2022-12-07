<div class="flex flex-col gap-4">
    <x-form>
        <div class="text-2xl font-bold">
            {{ __('Reset Password Request') }}
        </div>

        @if ($errors->any())
            <x-alert :errors="$errors->all()"/>
        @endif

        <x-form.email 
            label="Your registered email"
            wire:model.defer="email"
            required
            autofocus
        />

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