<div class="w-full flex flex-col gap-4">
    <x-box header="Sign-Up Information">
        <div class="flex flex-col divide-y">
            @foreach (array_merge(
                [
                    'Name' => $user->name,
                    'Login Email' => $user->email,
                ],

                ($signup = data_get($user->data, 'signup')) ? [
                    'Agreed to T&C' => data_get($signup, 'agree_tnc') ? 'Yes' : 'No',
                    'Agreed to Marketing' => data_get($signup, 'agree_marketing') ? 'Yes' : 'No',
                    'Registered IP Address' => data_get($signup, 'geo.ip', '--'),
                    'Registered From Country' => data_get($signup, 'geo.country', '--'),
                ] : [],
            ) as $key => $val)
                <x-field :label="$key" :value="$val"/>
            @endforeach

            <x-field label="Status" :badge="$user->status"/>
        </div>
    </x-box>

    <x-box header="Timestamps">
        <div class="flex flex-col divide-y">
            <x-field label="Join" :value="format_date($user->signup_at, 'datetime') ?? '--'"/>
            <x-field label="Login" :value="format_date($user->login_at, 'datetime') ?? '--'"/>
            <x-field label="Active" :value="format_date($user->last_active_at, 'datetime') ?? '--'"/>
            <x-field label="Blocked" :value="format_date($user->blocked_at, 'datetime') ?? '--'" :small="optional($user->blockedBy)->name"/>
        </div>
    </x-box>
</div>

