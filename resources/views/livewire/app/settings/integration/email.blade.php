<div class="lg:max-w-xl space-y-6">
    <atom:_heading size="lg">Email Configuration</atom:_heading>
    <atom:card>
        <atom:_form>
            <x-select label="app.label.email-provider" wire:model="settings.mailer" :options="[
                ['value' => 'smtp', 'label' => 'SMTP'],
                ['value' => 'mailgun', 'label' => 'Mailgun'],
            ]"/>

            @if ($settings['mailer'] === 'smtp')
                <x-input wire:model.defer="settings.smtp_host" label="app.label.smtp-host"/>
                <x-input wire:model.defer="settings.smtp_port" label="app.label.smtp-port"/>
                <x-input wire:model.defer="settings.smtp_username" label="app.label.smtp-username"/>
                <x-input wire:model.defer="settings.smtp_password" label="app.label.smtp-password"/>
                <x-select wire:model.defer="settings.smtp_encryption" label="app.label.smtp-encryption" :options="[
                    ['value' => 'ssl', 'label' => 'SSL'],
                    ['value' => 'tls', 'label' => 'TLS'],
                ]"/>
            @elseif ($settings['mailer'] === 'mailgun')
                <x-input wire:model.defer="settings.mailgun_domain" label="app.label.mailgun-domain"/>
                <x-input wire:model.defer="settings.mailgun_secret" label="app.label.mailgun-secret"/>
            @endif

            <atom:separator/>

            <x-input wire:model.defer="settings.notify_from" label="app.label.email-notify-from"/>
            <x-input wire:model.defer="settings.notify_to" label="app.label.email-notify-to"/>

            <atom:_button action="submit">Save</atom:_button>
        </atom:_form>
    </atom:card>
</div>
