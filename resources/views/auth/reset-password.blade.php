<div class="max-w-md mx-auto grid gap-10">
    <a class="mx-auto" href="/">
        <x-logo class="w-40"/>
    </a>

    <form wire:submit.prevent="save">
        <x-box>
            <div class="grid gap-6 p-5 md:p-10">
                <div class="text-2xl font-bold">
                    {{ __('Reset Password') }}
                </div>

                @if ($errors->any())
                    <x-alert :errors="$errors->all()"/>
                @endif

                <div class="grid gap-4">
                    <div class="font-medium text-gray-500">
                        {{ $email }}
                    </div>

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
                </div>

                <x-button.submit size="md" color="theme"
                    label="Reset Password"
                />
            </div>
        </x-box>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="flex items-center">
                <x-icon name="left-arrow-alt"></x-icon> {{ __('Back to login') }}
            </a>
        </div>
    </form>
</div>
