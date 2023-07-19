<div class="max-w-screen-md">
    <x-page-header title="Email Configurations"/>
    
    <x-form>
        <x-form.group cols="2">
            <x-form.select wire:model="settings.mailer" label="Email Provider" :options="[
                ['value' => 'smtp', 'label' => 'SMTP'],
                ['value' => 'mailgun', 'label' => 'Mailgun'],
            ]"/>
        </x-form.group>

        @if ($settings['mailer'] === 'smtp')
            <x-form.group cols="2">
                <x-form.text wire:model.defer="settings.smtp_host"/>
                <x-form.text wire:model.defer="settings.smtp_port"/>
                <x-form.text wire:model.defer="settings.smtp_username"/>
                <x-form.text wire:model.defer="settings.smtp_password"/>
                <x-form.select wire:model.defer="settings.smtp_encryption" :options="[
                    ['value' => 'ssl', 'label' => 'SSL'],
                    ['value' => 'tls', 'label' => 'TLS'],
                ]"/>
            </x-form.group>
        @endif
            
        @if ($settings['mailer'] === 'mailgun')
            <x-form.group cols="2">
                <x-form.text wire:model.defer="settings.mailgun_domain"/>
                <x-form.text wire:model.defer="settings.mailgun_secret"/>
            </x-form.group>
        @endif

        <x-form.group cols="2">
            <x-form.text wire:model.defer="settings.notify_from" label="Send Email Notification From"/>
            <x-form.text wire:model.defer="settings.notify_to" label="Send Email Notification To"/>
        </x-form.group>
    </x-form>
</div>
