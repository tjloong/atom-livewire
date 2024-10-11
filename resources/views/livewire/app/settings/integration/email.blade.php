<div class="lg:max-w-xl space-y-6">
    <atom:_heading size="lg">Email Configuration</atom:_heading>
    <atom:card>
        <atom:_form>
            <atom:_select wire:model="settings.mailer" label="email-provider">
                <atom:option value="smtp">SMTP</atom:option>
                <atom:option value="mailgun">Mailgun</atom:option>
            </atom:_select>

            @if ($settings['mailer'] === 'smtp')
                <atom:_input wire:model.defer="settings.smtp_host" label="smtp-host"/>
                <atom:_input wire:model.defer="settings.smtp_port" label="smtp-port"/>
                <atom:_input wire:model.defer="settings.smtp_username" label="smtp-username"/>
                <atom:_input wire:model.defer="settings.smtp_password" label="smtp-password"/>
                <atom:_select wire:model.defer="settings.smtp_encryption" label="smtp-encryption">
                    <atom:option value="ssl">SSL</atom:option>
                    <atom:option value="tls">TLS</atom:option>
                </atom:_select>
            @elseif ($settings['mailer'] === 'mailgun')
                <atom:_input wire:model.defer="settings.mailgun_domain" label="mailgun-domain"/>
                <atom:_input wire:model.defer="settings.mailgun_secret" label="mailgun-secret"/>
            @endif

            <atom:separator/>

            <atom:_input wire:model.defer="settings.notify_from" label="email-notify-from"/>
            <atom:_input wire:model.defer="settings.notify_to" label="email-notify-to"/>

            <atom:_button action="submit">Save</atom:_button>
        </atom:_form>
    </atom:card>
</div>
