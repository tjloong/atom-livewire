<x-drawer name="signup-update" wire:close="close()">
@if ($signup)
    <x-slot:heading>
        <x-contact-card :contact="$signup->user" class="mb-4"/>
    </x-slot:heading>

    <div class="p-5">
        <x-box>
            <div class="flex flex-col divide-y">
                <x-field label="common.label.name"
                    :value="$signup->user->name"/>
    
                <x-field label="common.label.login-email"
                    :value="$signup->user->email"/>
    
                <x-field label="signup.label.agreed-tnc"
                    :value="$signup->agree_tnc ? 'Yes' : 'No'"/>
    
                <x-field label="signup.label.agreed-marketing"
                    :value="$signup->agree_promo ? 'Yes' : 'No'"/>
    
                <x-field label="signup.label.reg-ip"
                    :value="data_get($signup->geo, 'ip') ?? '--'"/>
    
                <x-field label="signup.label.reg-country"
                    :value="data_get($signup->geo, 'country') ?? '--'"/>
    
                <x-field label="common.label.join-date" 
                    :value="format_date($signup->created_at, 'datetime') ?? '--'"/>
    
                <x-field label="common.label.login-date" 
                    :value="format_date($signup->user->login_at, 'datetime') ?? '--'"/>
    
                <x-field label="common.label.active-date" 
                    :value="format_date($signup->user->last_active_at, 'datetime') ?? '--'"/>
            </div>
        </x-box>        
    </div>
@endif
</x-drawer>