<x-form header="Email Configurations">
    <x-form.group>
        <x-form.text wire:model.defer="settings.notify_from" label="Send Email Notification From"/>
        <x-form.text wire:model.defer="settings.notify_to" label="Send Email Notification To"/>
        <x-form.select wire:model="settings.mailer" label="Email Provider" :options="[
            ['value' => 'smtp', 'label' => 'SMTP'],
            ['value' => 'mailgun', 'label' => 'Mailgun'],
        ]"/>
    
        @if ($settings['mailer'] === 'smtp')
            <x-form.text wire:model.defer="settings.smtp_host"/>
            <x-form.text wire:model.defer="settings.smtp_port"/>
            <x-form.text wire:model.defer="settings.smtp_username"/>
            <x-form.text wire:model.defer="settings.smtp_password"/>
            <x-form.select wire:model.defer="settings.smtp_encryption" :options="[
                ['value' => 'ssl', 'label' => 'SSL'],
                ['value' => 'tls', 'label' => 'TLS'],
            ]"/>
        @endif
    
        @if ($settings['mailer'] === 'mailgun')
            <x-form.text wire:model.defer="settings.mailgun_domain"/>
            <x-form.text wire:model.defer="settings.mailgun_secret"/>
        @endif
    </x-form.group>
</x-form>
