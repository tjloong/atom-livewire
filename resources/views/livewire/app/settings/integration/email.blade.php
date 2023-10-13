<div class="max-w-screen-md">
    <x-heading title="Email Configurations"/>
    
    <x-form>
        <x-form.group cols="2">
            <x-form.select label="atom::settings.label.email-provider"
                wire:model="settings.mailer"
                :options="[
                    ['value' => 'smtp', 'label' => 'SMTP'],
                    ['value' => 'mailgun', 'label' => 'Mailgun'],
                ]"/>
        </x-form.group>

        @if ($settings['mailer'] === 'smtp')
            <x-form.group cols="2">
                <x-form.text label="atom::settings.label.smtp-host"
                    wire:model.defer="settings.smtp_host"/>

                <x-form.text label="atom::settings.label.smtp-port"
                    wire:model.defer="settings.smtp_port"/>

                <x-form.text label="atom::settings.label.smtp-username"
                    wire:model.defer="settings.smtp_username"/>

                <x-form.text label="atom::settings.label.smtp-password"
                    wire:model.defer="settings.smtp_password"/>

                <x-form.select label="atom::settings.label.smtp-encryption"
                    wire:model.defer="settings.smtp_encryption"
                    :options="[
                        ['value' => 'ssl', 'label' => 'SSL'],
                        ['value' => 'tls', 'label' => 'TLS'],
                    ]"/>
            </x-form.group>
        @elseif ($settings['mailer'] === 'mailgun')
            <x-form.group cols="2">
                <x-form.text label="atom::settings.label.mailgun-domain"
                    wire:model.defer="settings.mailgun_domain"/>

                <x-form.text label="atom::settings.label.mailgun-secret"
                    wire:model.defer="settings.mailgun_secret"/>
            </x-form.group>
        @endif

        <x-form.group cols="2">
            <x-form.text label="atom::settings.label.email-notify-from"
                wire:model.defer="settings.notify_from"/>

            <x-form.text label="atom::settings.label.email-notify-to"
                wire:model.defer="settings.notify_to"/>
        </x-form.group>
    </x-form>
</div>
