<x-box>
    <x-slot name="header">Account Overview</x-slot>
    
    <div class="grid gap-4 p-5">
        <x-box.row label="Name">
            <div class="text-right">
                <div>{{ $account->name }}</div>
                @if ($account->status === 'blocked')
                    <div class="text-red-500 text-sm flex items-center gap-1">
                        <x-icon name="error-circle" size="xs" class="flex-shrink-0"/>
                        <div>Blocked at {{ format_date($account->blocked_at, 'datetime') }} by {{ $account->blocked_by_user->name ?? 'Unknown' }}</div>
                    </div>
                @endif
            </div>
        </x-box.row>

        <x-box.row label="Login Email">{{ $this->user->email ?? '--' }}</x-box.row>
        <x-box.row label="Agreed to T&C">{{ $account->agree_tnc ? 'Yes' : 'No' }}</x-box.row>
        <x-box.row label="Agreed to Marketing">{{ $account->agree_marketing ? 'Yes' : 'No' }}</x-box.row>
        <x-box.row label="Join Date">{{ format_date($account->created_at, 'datetime') }}</x-box.row>
        <x-box.row label="Status"><x-badge>{{ $account->status }}</x-badge></x-box.row>
        <x-box.row label="Registered IP Address">{{ $account->data->register_geo->ip ?? '--' }}</x-box.row>
        <x-box.row label="Registered From Country">{{ $account->data->register_geo->country ?? '--' }}</x-box.row>
    </div>
</x-box>
