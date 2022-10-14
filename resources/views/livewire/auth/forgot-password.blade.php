<div class="max-w-md mx-auto grid gap-10">
    <a class="mx-auto" href="/">
        <x-logo class="w-40"/>
    </a>

    <form wire:submit.prevent="send">
        <x-box>
            <div class="grid gap-6 p-5 md:p-10">
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

                <x-button.submit size="md" color="theme"
                    label="Send Request"
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