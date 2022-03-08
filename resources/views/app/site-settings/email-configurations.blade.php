<form wire:submit.prevent="submit">
    <x-box>
        <x-slot name="header">Email Configurations</x-slot>
        
        <div class="p-5">
            <x-input.text wire:model.defer="settings.notify_from" :error="$errors->first('settings.notify_from')" required>
                Send Email Notification From
            </x-input.text>

            <x-input.text wire:model.defer="settings.notify_to" :error="$errors->first('settings.notify_to')" required>
                Send Email Notification To
            </x-input.text>

            <x-input.select wire:model="settings.mailer" :options="[
                ['value' => 'smtp', 'label' => 'SMTP'],
                ['value' => 'mailgun', 'label' => 'Mailgun'],
            ]" required>
                Email Provider
            </x-input.select>

            <div>
                @if ($settings['mailer'] === 'smtp')
                    <x-input.text wire:model.defer="settings.smtp_host" :error="$errors->first('settings.smtp_host')" required>
                        SMPT Host
                    </x-input.text>
            
                    <x-input.text wire:model.defer="settings.smtp_port" :error="$errors->first('settings.smtp_port')" required>
                        SMTP Port
                    </x-input.text>
            
                    <x-input.text wire:model.defer="settings.smtp_username" :error="$errors->first('settings.smtp_username')" required>
                        SMTP Username
                    </x-input.text>
            
                    <x-input.password wire:model.defer="settings.smtp_password" :error="$errors->first('settings.smtp_password')" required>
                        SMTP Password
                    </x-input.password>
            
                    <x-input.select
                        wire:model.defer="settings.smtp_encryption"
                        :options="[
                            ['value' => 'ssl', 'label' => 'SSL'],
                            ['value' => 'tls', 'label' => 'TLS'],
                        ]"
                    >
                        SMTP Encryption
                    </x-input.select>    
                @endif
            </div>

            <div>
                @if ($settings['mailer'] === 'mailgun')
                    <x-input.text wire:model.defer="settings.mailgun_domain" :error="$errors->first('settings.mailgun_domain')">
                        Mailgun Domain
                    </x-input.text>

                    <x-input.text wire:model.defer="settings.mailgun_secret" :error="$errors->first('settings.mailgun_secret')">
                        Mailgun Secret
                    </x-input.text>
                @endif
            </div>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>