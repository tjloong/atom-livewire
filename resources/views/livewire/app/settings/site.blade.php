<div class="max-w-screen-md">
    <x-heading title="app.label.site"/>

    <x-form>
        <x-group cols="2">
            <x-form.text wire:model.defer="settings.site_name" label="app.label.site-name"/>
            <x-form.text wire:model.defer="settings.site_description" label="app.label.site-description"/>
        </x-group>

        <x-group cols="2" heading="app.label.site-contact-information">
            <x-form.text wire:model.defer="settings.contact_name" label="app.label.site-contact-name"/>
            <x-form.text wire:model.defer="settings.contact_phone" label="app.label.site-contact-phone"/>
            <x-form.text wire:model.defer="settings.contact_email" label="app.label.site-contact-email"/>
            <x-form.text wire:model.defer="settings.contact_address" label="app.label.site-contact-address"/>
            <div class="col-span-2">
                <x-form.textarea wire:model.defer="settings.contact_map" label="app.label.site-contact-map"/>
            </div>
        </x-group>

        <x-group cols="2" heading="app.label.whatsapp-bubble">
            <x-form.text wire:model.defer="settings.whatsapp_number" label="app.label.whatsapp-number"/>
            <x-form.text wire:model.defer="settings.whatsapp_text" label="app.label.whatsapp-text"/>
            <x-form.checkbox wire:model="settings.whatsapp_bubble" label="app.label.whatsapp-bubble"/>
        </x-group>

        <x-group cols="2" heading="app.label.social-media-page">
            <x-form.text wire:model.defer="settings.facebook_url" label="app.label.facebook-url"/>
            <x-form.text wire:model.defer="settings.instagram_url" label="app.label.instagram-url"/>
            <x-form.text wire:model.defer="settings.twitter_url" label="app.label.twitter-url"/>
            <x-form.text wire:model.defer="settings.linkedin_url" label="app.label.linkedin-url"/>
            <x-form.text wire:model.defer="settings.youtube_url" label="app.label.youtube-url"/>
            <x-form.text wire:model.defer="settings.spotify_url" label="app.label.spotify-url"/>
            <x-form.text wire:model.defer="settings.tiktok_url" label="app.label.tiktok-url"/>
        </x-group>

        <x-group cols="2" heading="SEO">
            <x-form.text wire:model.defer="settings.meta_title" label="app.label.meta-title"/>
            <x-form.text wire:model.defer="settings.meta_description" label="app.label.meta-description"/>
            <x-form.text wire:model.defer="settings.meta_image" label="app.label.meta-image"/>
        </x-group>

        <x-group cols="2" heading="app.label.site-analytic">
            <x-form.text wire:model.defer="settings.ga_id" label="app.label.ga-id"/>
            <x-form.text wire:model.defer="settings.gtm_id" label="app.label.gtm-id"/>
            <x-form.text wire:model.defer="settings.fbpixel_id" label="app.label.fbpixel-id"/>
        </x-group>

        <x-group cols="2" heading="app.label.recaptcha">
            <x-form.text wire:model.defer="settings.recaptcha_site_key" label="app.label.site-key"/>
            <x-form.text wire:model.defer="settings.recaptcha_secret_key" label="app.label.secret-key"/>
        </x-group>
    </x-form>
</div>