<div class="max-w-md mx-auto grid gap-10">
    <a class="w-40 mx-auto" href="/">
        <x-atom-logo/>
    </a>

    <form wire:submit.prevent="save">
        <x-box>
            <div class="grid gap-6 p-5 md:p-10">
                <div class="text-2xl font-bold">
                    Reset Password
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-800 rounded p-4 grid gap-2">
                        @foreach ($errors->all() as $error)
                        <div class="flex gap-2">
                            <x-icon name="x" class="py-0.5"/>
                            <div class="text-sm">
                                {{ $error }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                <div class="grid gap-4">
                    <div class="font-medium text-gray-500">
                        {{ $email }}
                    </div>

                    <div>
                        <div class="font-medium text-gray-500 text-xs uppercase mb-2">New Password</div>
                        <input type="password" wire:model.defer="password" class="form-input w-full" required autofocus>
                    </div>
                    
                    <div>
                        <div class="font-medium text-gray-500 text-xs uppercase mb-2">Confirm Password</div>
                        <input type="password" wire:model.defer="passwordConfirm" class="form-input w-full" required>
                    </div>
                </div>

                <x-button type="submit" size="md" wire:loading.class="loading">
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