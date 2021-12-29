<div class="max-w-md mx-auto flex flex-col items-center">
    <a class="w-40 mb-4" href="/">
        <x-atom-logo/>
    </a>

    <form wire:submit.prevent="save" class="w-full">
        <x-box>
            <div class="p-5 md:p-10">
                <div class="text-2xl font-bold mb-2">
                    Reset Password
                </div>

                <div class="font-medium text-gray-500 mb-6">
                    {{ $email }}
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
                
                <div class="w-full mb-4">
                    <div class="font-medium text-gray-500 text-xs uppercase mb-2">New Password</div>
                    <input type="password" wire:model.defer="password" class="form-input w-full" required autofocus>
                </div>
                
                <div class="w-full mb-6">
                    <div class="font-medium text-gray-500 text-xs uppercase mb-2">Confirm Password</div>
                    <input type="password" wire:model.defer="passwordConfirm" class="form-input w-full" required>
                </div>

                <x-button type="submit" size="md" class="w-full" wire:loading.class="loading">
                    Reset Password
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
