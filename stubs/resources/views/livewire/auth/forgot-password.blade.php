<div class="max-w-md mx-auto flex flex-col items-center">
    <a class="w-40 mb-10" href="/">
        <x-atom-logo/>
    </a>

    <form wire:submit.prevent="send" class="w-full">
        <x-box>
            <div class="p-5 md:p-10">
                <div class="text-2xl font-bold mb-6">
                    Reset Password Request
                </div>

                @if ($errors->any())
                    <div class="mb-4 text-sm bg-red-100 text-red-800 rounded p-4">
                        @foreach ($errors->all() as $error)
                        <div class="flex">
                            <x-icon name="x" />
                            <div class="leading-relaxed">
                                {{ $error }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                <div class="mb-5 w-full">
                    <div class="font-medium text-gray-500 text-xs uppercase mb-2">Your registered email</div>
                    <input type="email" class="w-full form-input" wire:model.defer="email" required autofocus>
                </div>

                <x-button type="submit" size="md" class="w-full" wire:loading.class="loading">
                    Send Request
                </x-button>
            </div>
        </x-box>

        <div class="text-sm mt-4">
            <a href="{{ route('login') }}" class="flex items-center">
                <x-icon name="left-arrow-alt"></x-icon> Back to login
            </a>
        </div>
    </form>
</div>