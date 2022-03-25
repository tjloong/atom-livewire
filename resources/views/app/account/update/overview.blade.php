<x-box>
    <x-slot name="header">Account Overview</x-slot>
    
    <div class="p-5">
        <x-input.field>
            <x-slot name="label">Name</x-slot>

            <div class="grid gap-1">
                <div>{{ $account->name }}</div>

                @if ($account->status === 'blocked')
                    <div class="text-red-500 text-sm flex items-center gap-1">
                        <x-icon name="error-circle" size="xs" class="flex-shrink-0"/>
                        <div>Blocked at {{ format_date($account->blocked_at, 'datetime') }} by {{ $account->blocked_by_user->name ?? 'Unknown' }}</div>
                    </div>
                @endif
            </div>
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Login Email</x-slot>
            {{ $account->email ?? '--' }}
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Agreed to T&C/Privacy Policy</x-slot>
            {{ $account->agree_tnc ? 'Yes' : 'No' }}
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Agreed to receiving promotions and marketing</x-slot>
            {{ $account->agree_marketing ? 'Yes' : 'No' }}
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Join Date</x-slot>
            {{ format_date($account->created_at, 'datetime') }}
        </x-input.field>

        <x-input.field>
            <x-slot name="label">Status</x-slot>
            <x-badge>{{ $account->status }}</x-badge>
        </x-input.field>
    </div>
</x-box>
