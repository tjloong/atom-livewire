<div class="max-w-screen-md">
    <x-heading title="settings.heading.email"/>
    
    <x-form>
        <x-group cols="2">
            <x-form.select label="settings.label.email-provider"
                wire:model="settings.mailer"
                :options="[
                    ['value' => 'smtp', 'label' => 'SMTP'],
                    ['value' => 'mailgun', 'label' => 'Mailgun'],
                ]"/>
        </x-group>

        @if ($settings['mailer'] === 'smtp')
            <x-group cols="2">
                <x-form.text label="settings.label.smtp-host"
                    wire:model.defer="settings.smtp_host"/>

                <x-form.text label="settings.label.smtp-port"
                    wire:model.defer="settings.smtp_port"/>

                <x-form.text label="settings.label.smtp-username"
                    wire:model.defer="settings.smtp_username"/>

                <x-form.text label="settings.label.smtp-password"
                    wire:model.defer="settings.smtp_password"/>

                <x-form.select label="settings.label.smtp-encryption"
                    wire:model.defer="settings.smtp_encryption"
                    :options="[
                        ['value' => 'ssl', 'label' => 'SSL'],
                        ['value' => 'tls', 'label' => 'TLS'],
                    ]"/>
            </x-group>
        @elseif ($settings['mailer'] === 'mailgun')
            <x-group cols="2">
                <x-form.text label="settings.label.mailgun-domain"
                    wire:model.defer="settings.mailgun_domain"/>

                <x-form.text label="settings.label.mailgun-secret"
                    wire:model.defer="settings.mailgun_secret"/>
            </x-group>
        @endif

        <x-group cols="2">
            <x-form.text label="settings.label.email-notify-from"
                wire:model.defer="settings.notify_from"/>

            <x-form.text label="settings.label.email-notify-to"
                wire:model.defer="settings.notify_to"/>
        </x-group>
    </x-form>
</div>
