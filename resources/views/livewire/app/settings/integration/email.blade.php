<div class="max-w-screen-md">
    <x-heading title="app.label.email-settings" apa lg/>
    
    <x-form>
        <div class="grid divide-y">
            <x-inputs cols>
                <x-select label="app.label.email-provider" wire:model="settings.mailer" :options="[
                    ['value' => 'smtp', 'label' => 'SMTP'],
                    ['value' => 'mailgun', 'label' => 'Mailgun'],
                ]"/>
            </x-inputs>

            @if ($settings['mailer'] === 'smtp')
                <x-inputs cols>
                    <x-input wire:model.defer="settings.smtp_host" label="app.label.smtp-host"/>
                    <x-input wire:model.defer="settings.smtp_port" label="app.label.smtp-port"/>
                    <x-input wire:model.defer="settings.smtp_username" label="app.label.smtp-username"/>
                    <x-input wire:model.defer="settings.smtp_password" label="app.label.smtp-password"/>
                    <x-select wire:model.defer="settings.smtp_encryption" label="app.label.smtp-encryption" :options="[
                        ['value' => 'ssl', 'label' => 'SSL'],
                        ['value' => 'tls', 'label' => 'TLS'],
                    ]"/>
                </x-inputs>
            @elseif ($settings['mailer'] === 'mailgun')
                <x-inputs cols>
                    <x-input wire:model.defer="settings.mailgun_domain" label="app.label.mailgun-domain"/>
                    <x-input wire:model.defer="settings.mailgun_secret" label="app.label.mailgun-secret"/>
                </x-inputs>
            @endif

            <x-inputs cols>
                <x-input wire:model.defer="settings.notify_from" label="app.label.email-notify-from"/>
                <x-input wire:model.defer="settings.notify_to" label="app.label.email-notify-to"/>
            </x-inputs>
        </div>
    </x-form>
</div>
