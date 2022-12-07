<div class="flex flex-col gap-4">
    <x-form>
        <div class="text-2xl font-bold">
            {{ __('Reset Password') }}
        </div>

        @if ($errors->any())
            <x-alert :errors="$errors->all()"/>
        @endif

        <x-form.field label="Email">
            {{ $email }}
        </x-form.field>

        <x-form.password
            label="New Password"
            wire:model.defer="password"
            required
            autofocus
        />

        <x-form.password
            label="Confirm Password"
            wire:model.defer="passwordConfirm"
            required
            autofocus
        />

        <x-slot:foot>
            <x-button.submit size="md" block
                label="Reset Password"
            />
        </x-slot:foot>
    </x-form>

    <a href="{{ route('login') }}" class="flex items-center gap-2">
        <x-icon name="arrow-left"></x-icon> {{ __('Back to login') }}
    </a>
</div>
