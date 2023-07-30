<x-drawer name="signup-update" 
    header="Sign-Up"
    :show="!empty($signup)"
>
@if ($signup)
    <div class="flex flex-col divide-y px-2">
        <x-field label="Name" :value="$signup->user->name"/>
        <x-field label="Login Email" :value="$signup->user->email"/>
        <x-field label="Agreed to T&C" :value="$signup->agree_tnc ? 'Yes' : 'No'"/>
        <x-field label="Agreed to Marketing" :value="$signup->agree_promo ? 'Yes' : 'No'"/>
        <x-field label="Registered IP Address" :value="data_get($signup->geo, 'ip') ?? '--'"/>
        <x-field label="Registered From Country" :value="data_get($signup->geo, 'country') ?? '--'"/>
        <x-field label="Join Date" :value="format_date($signup->created_at, 'datetime') ?? '--'"/>
        <x-field label="Login Date" :value="format_date($signup->user->login_at, 'datetime') ?? '--'"/>
        <x-field label="Active Date" :value="format_date($signup->user->last_active_at, 'datetime') ?? '--'"/>
    </div>
@endif
</x-drawer>