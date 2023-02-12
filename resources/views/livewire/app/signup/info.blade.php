<x-box header="Sign-Up Information">
    <div class="grid divide-y">
        <x-box.row label="Name">{{ $user->name }}</x-box.row>
        <x-box.row label="Login Email">{{ $user->email }}</x-box.row>
        <x-box.row label="Sign-Up Timestamp">{{ format_date($user->signup_at, 'datetime') }}</x-box.row>
        <x-box.row label="Last Login Timestamp">{{ format_date($user->login_at, 'datetime') }}</x-box.row>
        <x-box.row label="Last Active Timestamp">{{ format_date($user->last_active_at, 'datetime') }}</x-box.row>
        <x-box.row label="Status"><x-badge>{{ $user->status }}</x-badge></x-box.row>

        @if ($user->status === 'blocked')
            <x-box.row label="Blocked Timestamp">{{ format_date($user->blocked_at, 'datetime') }}</x-box.row>
            <x-box.row label="Blocked By">{{ $user->blockedBy->name ?? 'Unknown' }}</x-box.row>
        @endif

        @if ($signup = data_get($user->data, 'signup'))
            <x-box.row label="Agreed to T&C">{{ data_get($signup, 'agree_tnc') ? 'Yes' : 'No' }}</x-box.row>
            <x-box.row label="Agreed to Marketing">{{ data_get($signup, 'agree_marketing') ? 'Yes' : 'No' }}</x-box.row>
            <x-box.row label="Registered IP Address">{{ data_get($signup, 'geo.ip', '--') }}</x-box.row>
            <x-box.row label="Registered From Country">{{ data_get($signup, 'geo.country', '--') }}</x-box.row>
        @endif
    </div>
</x-box>
