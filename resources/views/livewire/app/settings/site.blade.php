<div class="max-w-screen-md">
    <x-heading title="settings.heading.site"/>

    <x-form>
        <x-form.group cols="2">
            <x-form.text label="settings.label.site-name"
                wire:model.defer="settings.site_name"/>

            <x-form.text label="settings.label.site-description"
                wire:model.defer="settings.site_description"/>
        </x-form.group>

        <x-form.group cols="2" heading="settings.heading.site-contact-info">
            <x-form.text label="settings.label.contact-name"
                wire:model.defer="settings.contact_name"/>

            <x-form.text label="settings.label.contact-phone"
                wire:model.defer="settings.contact_phone"/>

            <x-form.text label="settings.label.contact-email"
                wire:model.defer="settings.contact_email"/>

            <x-form.text label="settings.label.contact-address"
                wire:model.defer="settings.contact_address"/>

            <x-form.text label="settings.label.contact-map"
                wire:model.defer="settings.contact_map"/>
        </x-form.group>

        <x-form.group cols="2" heading="settings.heading.site-whatsapp">
            <x-form.text label="settings.label.whatsapp-number"
                wire:model.defer="settings.whatsapp_number"/>

            <x-form.text label="settings.label.whatsapp-text"
                wire:model.defer="settings.whatsapp_text"/>

            <x-form.checkbox label="settings.label.whatsapp-bubble"
                wire:model="settings.whatsapp_bubble"/>
        </x-form.group>

        <x-form.group cols="2" heading="settings.heading.site-social-media-page">
            <x-form.text label="settings.label.facebook-url"
                wire:model.defer="settings.facebook_url"/>

            <x-form.text label="settings.label.instagram-url"
                wire:model.defer="settings.instagram_url"/>

            <x-form.text label="settings.label.twitter-url"
                wire:model.defer="settings.twitter_url"/>

            <x-form.text label="settings.label.linkedin-url"
                wire:model.defer="settings.linkedin_url"/>

            <x-form.text label="settings.label.youtube-url"
                wire:model.defer="settings.youtube_url"/>

            <x-form.text label="settings.label.spotify-url"
                wire:model.defer="settings.spotify_url"/>

            <x-form.text label="settings.label.tiktok-url"
                wire:model.defer="settings.tiktok_url"/>
        </x-form.group>

        <x-form.group cols="2" heading="SEO">
            <x-form.text label="settings.label.meta-title"
                wire:model.defer="settings.meta_title"/>

            <x-form.text label="settings.label.meta-description"
                wire:model.defer="settings.meta_description"/>

            <x-form.text label="settings.label.meta-image"
                wire:model.defer="settings.meta_image"/>
        </x-form.group>

        <x-form.group cols="2" heading="settings.heading.site-analytic">
            <x-form.text label="settings.label.ga-id"
                wire:model.defer="settings.ga_id"/>

            <x-form.text label="settings.label.gtm-id"
                wire:model.defer="settings.gtm_id"/>

            <x-form.text label="settings.label.fbpixel-id"
                wire:model.defer="settings.fbpixel_id"/>
        </x-form.group>

        <x-form.group cols="2" heading="settings.heading.site-recaptcha">
            <x-form.text label="settings.label.site-key"
                wire:model.defer="settings.recaptcha_site_key"/>
                
            <x-form.text label="settings.label.secret-key"
                wire:model.defer="settings.recaptcha_secret_key"/>
        </x-form.group>
    </x-form>
</div>