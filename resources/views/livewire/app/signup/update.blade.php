<x-drawer name="signup-update">
@if ($signup)
    <x-slot:heading title="{!! $signup->user->name !!}"></x-slot:heading>

    <x-box>
        <div class="flex flex-col divide-y">
            <x-field label="atom::common.label.name"
                :value="$signup->user->name"/>

            <x-field label="atom::common.label.login-email"
                :value="$signup->user->email"/>

            <x-field label="atom::signup.label.agreed-tnc"
                :value="$signup->agree_tnc ? 'Yes' : 'No'"/>

            <x-field label="atom::signup.label.agreed-marketing"
                :value="$signup->agree_promo ? 'Yes' : 'No'"/>

            <x-field label="atom::signup.label.reg-ip"
                :value="data_get($signup->geo, 'ip') ?? '--'"/>

            <x-field label="atom::signup.label.reg-country"
                :value="data_get($signup->geo, 'country') ?? '--'"/>

            <x-field label="atom::common.label.join-date" 
                :value="format_date($signup->created_at, 'datetime') ?? '--'"/>

            <x-field label="atom::common.label.login-date" 
                :value="format_date($signup->user->login_at, 'datetime') ?? '--'"/>

            <x-field label="atom::common.label.active-date" 
                :value="format_date($signup->user->last_active_at, 'datetime') ?? '--'"/>
        </div>
    </x-box>
@endif
</x-drawer>