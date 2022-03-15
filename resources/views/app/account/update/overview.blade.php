<x-box>
    <x-slot name="header">Account Overview</x-slot>
    
    <div class="p-5">
        <x-input.field>
            <x-slot name="label">Name</x-slot>

            <div class="grid gap-1">
                <div>{{ $user->name }}</div>

                @if ($user->account->status === 'blocked')
                    <div class="text-red-500 text-sm flex items-center gap-1">
                        <x-icon name="error-circle" size="xs" class="flex-shrink-0"/>
                        <div>Blocked at {{ format_date($user->account->blocked_at, 'datetime') }} by {{ $user->account->blocked_by_user->name ?? 'Unknown' }}</div>
                    </div>
                @endif
            </div>
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Email</x-slot>
            {{ $user->account->email ?? $user->email ?? '--' }}
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Phone</x-slot>
            {{ $user->account->phone ?? '--' }}
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Agreed to T&C/Privacy Policy</x-slot>
            {{ $user->account->agree_tnc ? 'Yes' : 'No' }}
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Agreed to receiving promotions and marketing</x-slot>
            {{ $user->account->agree_marketing ? 'Yes' : 'No' }}
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Sign-up Date</x-slot>
            {{ format_date($user->created_at, 'datetime') }}
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Status</x-slot>
            <x-badge>{{ $user->account->status }}</x-badge>
        </x-input.field>
    </div>
</x-box>
