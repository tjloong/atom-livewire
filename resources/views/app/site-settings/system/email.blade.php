<x-form header="Email Configurations">
    <x-form.text 
        label="Send Email Notification From"
        wire:model.defer="settings.notify_from" 
        :error="$errors->first('settings.notify_from')" 
        required
    />

    <x-form.text 
        label="Send Email Notification To"
        wire:model.defer="settings.notify_to" 
        :error="$errors->first('settings.notify_to')" 
        required
    />

    <x-form.select 
        label="Email Provider"
        wire:model="settings.mailer" 
        :options="[
            ['value' => 'smtp', 'label' => 'SMTP'],
            ['value' => 'mailgun', 'label' => 'Mailgun'],
        ]" 
        required
    />

    @if ($settings['mailer'] === 'smtp')
        <x-form.text 
            label="SMPT Host"
            wire:model.defer="settings.smtp_host" 
            :error="$errors->first('settings.smtp_host')" 
            required
        />

        <x-form.text 
            label="SMTP Port"
            wire:model.defer="settings.smtp_port" 
            :error="$errors->first('settings.smtp_port')" 
            required
        />

        <x-form.text 
            label="SMTP Username"
            wire:model.defer="settings.smtp_username" 
            :error="$errors->first('settings.smtp_username')" 
            required
        />

        <x-form.text 
            label="SMTP Password"
            wire:model.defer="settings.smtp_password" 
            :error="$errors->first('settings.smtp_password')" 
            required
        />

        <x-form.select
            label="SMTP Encryption"
            wire:model.defer="settings.smtp_encryption"
            :options="[
                ['value' => 'ssl', 'label' => 'SSL'],
                ['value' => 'tls', 'label' => 'TLS'],
            ]"
        />
    @endif

    @if ($settings['mailer'] === 'mailgun')
        <x-form.text 
            label="Mailgun Domain"
            wire:model.defer="settings.mailgun_domain" 
            :error="$errors->first('settings.mailgun_domain')"
        />

        <x-form.text 
            label="Mailgun Secret"
            wire:model.defer="settings.mailgun_secret" 
            :error="$errors->first('settings.mailgun_secret')"
        />
    @endif

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
