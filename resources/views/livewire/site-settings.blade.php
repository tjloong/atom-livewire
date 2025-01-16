<div class="max-w-screen-md">
    <atom:card>
        <atom:_form>
            <atom:_heading size="xl">@t('site-settings')</atom:_heading>

            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="settings.site_name" label="site-name"/>
                <atom:_input wire:model.defer="settings.site_description" label="site-description"/>
                <atom:_input wire:model.defer="settings.documentation_url" label="documentation-url"/>
            </div>

            <atom:separator/>
            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="settings.contact_name" label="site-contact-name"/>
                <atom:_input wire:model.defer="settings.contact_phone" label="site-contact-phone"/>
                <atom:_input wire:model.defer="settings.contact_email" label="site-contact-email"/>
                <atom:_input wire:model.defer="settings.contact_address" label="site-contact-address"/>
                <div class="col-span-2">
                    <atom:_textarea wire:model.defer="settings.contact_map" label="site-contact-map"/>
                </div>
            </div>

            <atom:separator/>
            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="settings.whatsapp_number" label="whatsapp-number"/>
                <atom:_input wire:model.defer="settings.whatsapp_text" label="whatsapp-text"/>
                <atom:_checkbox wire:model="settings.whatsapp_bubble" label="whatsapp-bubble"/>
            </div>

            <atom:separator/>
            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="settings.facebook_url" label="facebook-url"/>
                <atom:_input wire:model.defer="settings.instagram_url" label="instagram-url"/>
                <atom:_input wire:model.defer="settings.twitter_url" label="twitter-url"/>
                <atom:_input wire:model.defer="settings.linkedin_url" label="linkedin-url"/>
                <atom:_input wire:model.defer="settings.youtube_url" label="youtube-url"/>
                <atom:_input wire:model.defer="settings.spotify_url" label="spotify-url"/>
                <atom:_input wire:model.defer="settings.tiktok_url" label="tiktok-url"/>
            </div>

            <atom:separator/>
            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="settings.meta_title" label="meta-title"/>
                <atom:_input wire:model.defer="settings.meta_description" label="meta-description"/>
                <atom:_input wire:model.defer="settings.meta_image" label="meta-image"/>
            </div>

            <atom:_button action="submit">@t('save')</atom:_button>
        </atom:_form>
    </atom:card>
</div>
