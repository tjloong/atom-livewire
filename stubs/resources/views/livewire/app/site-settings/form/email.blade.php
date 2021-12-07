<form wire:submit.prevent="save" class="max-w-lg">
    <x-box>
        <x-slot name="header">Site Email Configurations</x-slot>

        <div class="p-5">
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

            <x-input.text wire:model.defer="settings.notify_from" :error="$errors->first('settings.notify_from')" required>
                Send Email Notification From
            </x-input.text>

            <x-input.text wire:model.defer="settings.notify_to" :error="$errors->first('settings.notify_to')" required>
                Send Email Notification To
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>