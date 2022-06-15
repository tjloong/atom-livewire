<x-box>
    <x-slot name="header">Account Overview</x-slot>
    
    <div class="grid gap-6 p-5">
        <x-form.field label="Name">
            <div class="grid gap-1">
                <div>{{ $account->name }}</div>

                @if ($account->status === 'blocked')
                    <div class="text-red-500 text-sm flex items-center gap-1">
                        <x-icon name="error-circle" size="xs" class="flex-shrink-0"/>
                        <div>Blocked at {{ format_date($account->blocked_at, 'datetime') }} by {{ $account->blocked_by_user->name ?? 'Unknown' }}</div>
                    </div>
                @endif
            </div>
        </x-form.field>

        <x-form.field label="Login Email">
            {{ $this->user->email ?? '--' }}
        </x-form.field>

        <x-form.field label="Agreed to T&C/Privacy Policy">
            {{ $account->agree_tnc ? 'Yes' : 'No' }}
        </x-form.field>

        <x-form.field label="Agreed to receiving promotions and marketing">
            {{ $account->agree_marketing ? 'Yes' : 'No' }}
        </x-form.field>

        <x-form.field label="Join Date">
            {{ format_date($account->created_at, 'datetime') }}
        </x-form.field>

        <x-form.field label="Registered IP Address">
            {{ $account->data->register_geo->ip ?? '--' }}
        </x-form.field>

        <x-form.field label="Registered From Country">
            {{ $account->data->register_geo->country ?? '--' }}
        </x-form.field>

        <x-form.field label="Status">
            <x-badge>{{ $account->status }}</x-badge>
        </x-form.field>
    </div>
</x-box>
