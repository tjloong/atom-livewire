<x-drawer wire:close="$emit('closeSignup')">
@if ($signup)
    <x-slot:heading>
        <x-media-object :label="$signup->user->name" :caption="$signup->user->email" avatar/>
    </x-slot:heading>

    <div class="p-5">
        <x-box>
            <x-fieldset>
                <x-field label="app.label.name" :value="$signup->user->name"/>
                <x-field label="app.label.login-email" :value="$signup->user->email"/>
                <x-field label="app.label.agreed-tnc" :value="$signup->agree_tnc ? 'Yes' : 'No'"/>
                <x-field label="app.label.agreed-marketing" :value="$signup->agree_promo ? 'Yes' : 'No'"/>
                <x-field label="app.label.reg-ip" :value="data_get($signup->geo, 'ip') ?? '--'"/>
                <x-field label="app.label.reg-country" :value="data_get($signup->geo, 'country') ?? '--'"/>
                <x-field label="app.label.join-date" :value="format($signup->created_at, 'datetime') ?? '--'"/>
                <x-field label="app.label.login-date" :value="format($signup->user->login_at, 'datetime') ?? '--'"/>
                <x-field label="app.label.active-date" :value="format($signup->user->last_active_at, 'datetime') ?? '--'"/>
            </x-fieldset>
        </x-box>        
    </div>
@endif
</x-drawer>