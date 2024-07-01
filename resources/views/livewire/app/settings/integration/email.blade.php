<div class="max-w-screen-md">
    <x-heading title="settings.heading.email" lg/>
    
    <x-form>
        <x-group cols="2">
            <x-select label="settings.label.email-provider" wire:model="settings.mailer" :options="[
                ['value' => 'smtp', 'label' => 'SMTP'],
                ['value' => 'mailgun', 'label' => 'Mailgun'],
            ]"/>
        </x-group>

        @if ($settings['mailer'] === 'smtp')
            <x-group cols="2">
                <x-input wire:model.defer="settings.smtp_host" label="settings.label.smtp-host"/>
                <x-input wire:model.defer="settings.smtp_port" label="settings.label.smtp-port"/>
                <x-input wire:model.defer="settings.smtp_username" label="settings.label.smtp-username"/>
                <x-input wire:model.defer="settings.smtp_password" label="settings.label.smtp-password"/>
                <x-select wire:model.defer="settings.smtp_encryption" label="settings.label.smtp-encryption" :options="[
                    ['value' => 'ssl', 'label' => 'SSL'],
                    ['value' => 'tls', 'label' => 'TLS'],
                ]"/>
            </x-group>
        @elseif ($settings['mailer'] === 'mailgun')
            <x-group cols="2">
                <x-input wire:model.defer="settings.mailgun_domain" label="settings.label.mailgun-domain"/>
                <x-input wire:model.defer="settings.mailgun_secret" label="settings.label.mailgun-secret"/>
            </x-group>
        @endif

        <x-group cols="2">
            <x-input wire:model.defer="settings.notify_from" label="settings.label.email-notify-from"/>
            <x-input wire:model.defer="settings.notify_to" label="settings.label.email-notify-to"/>
        </x-group>
    </x-form>
</div>
