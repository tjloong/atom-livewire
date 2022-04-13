<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="__('Create User')" back/>

    <div class="grid gap-6">
        @if ($user->account_id !== auth()->user()->account_id)
            <x-box>
                <div class="p-5">
                    <x-input.field>
                        <x-slot:label>{{ __('Account Name') }}</x-slot:label>
                        <div class="text-lg font-bold">{{ $user->account->name }}</div>
                        <div class="font-medium text-gray-500">
                            @if ($email = $user->account->email)
                                {{ $email }}<br>
                            @endif
                            @if ($phone = $user->account->phone)
                                {{ $phone }}<br>
                            @endif
                        </div>
                    </x-input.field>
                </div>
            </x-box>
        @endif

        @livewire('atom.app.user.form', compact('user'))
    </div>
</div>