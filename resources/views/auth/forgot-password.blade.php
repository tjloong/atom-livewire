<div class="max-w-md mx-auto grid gap-10">
    <a class="w-40 mx-auto" href="/">
        <x-atom-logo/>
    </a>

    <form wire:submit.prevent="send">
        <x-box>
            <div class="grid gap-6 p-5 md:p-10">
                <div class="text-2xl font-bold">
                    Reset Password Request
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

                <div>
                    <div class="font-medium text-gray-500 text-sm uppercase mb-2">Your registered email</div>
                    <input type="email" class="w-full form-input" wire:model.defer="email" required autofocus>
                </div>

                <x-button type="submit" size="md" wire:loading.class="loading">
                    Send Request
                </x-button>
            </div>
        </x-box>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="flex items-center">
                <x-icon name="left-arrow-alt"></x-icon> Back to login
            </a>
        </div>
    </form>
</div>