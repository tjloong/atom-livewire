<div class="max-w-screen-md">
    <x-heading title="settings.heading.site"/>

    <x-form>
        <x-form.group cols="2">
            <x-form.text label="settings.label.site-name"
                wire:model.defer="settings.site_name"/>

            <x-form.text label="settings.label.site-description"
                wire:model.defer="settings.site_description"/>
        </x-form.group>

        <x-form.group cols="2" heading="Contact Information">
            <x-form.text label="settings.label.contact-name"
                wire:model.defer="settings.site_contact_name"/>

            <x-form.text label="settings.label.contact-phone"
                wire:model.defer="settings.site_contact_phone"/>

            <x-form.text label="settings.label.contact-email"
                wire:model.defer="settings.site_contact_email"/>

            <x-form.text label="settings.label.contact-address"
                wire:model.defer="settings.site_contact_address"/>

            <x-form.text label="settings.label.contact-map"
                wire:model.defer="settings.site_contact_map"/>
        </x-form.group>

        <x-form.group cols="2" heading="Whatsapp">
            <x-form.text label="settings.label.whatsapp-number"
                wire:model.defer="settings.site_whatsapp_number"/>

            <x-form.text label="settings.label.whatsapp-text"
                wire:model.defer="settings.site_whatsapp_text"/>

            <x-form.checkbox label="settings.label.whatsapp-bubble"
                wire:model="settings.site_whatsapp_bubble"/>
        </x-form.group>

        <x-form.group cols="2" heading="SEO">
            <x-form.text label="settings.label.meta-title"
                wire:model.defer="settings.site_meta_title"/>

            <x-form.text label="settings.label.meta-description"
                wire:model.defer="settings.site_meta_description"/>

            <x-form.text label="settings.label.meta-image"
                wire:model.defer="settings.site_meta_image"/>
        </x-form.group>

        <x-form.group cols="2" heading="Analytics">
            <x-form.text label="settings.label.ga-id"
                wire:model.defer="settings.site_ga_id"/>

            <x-form.text label="settings.label.gtm-id"
                wire:model.defer="settings.site_gtm_id"/>

            <x-form.text label="settings.label.fbpixel-id"
                wire:model.defer="settings.site_fbpixel_id"/>
        </x-form.group>

        <x-form.group cols="2" heading="Social Media Pages">
            <x-form.text label="settings.label.facebook-url"
                wire:model.defer="settings.site_facebook_url"/>

            <x-form.text label="settings.label.instagram-url"
                wire:model.defer="settings.site_instagram_url"/>

            <x-form.text label="settings.label.twitter-url"
                wire:model.defer="settings.site_twitter_url"/>

            <x-form.text label="settings.label.linkedin-url"
                wire:model.defer="settings.site_linkedin_url"/>

            <x-form.text label="settings.label.youtube-url"
                wire:model.defer="settings.site_youtube_url"/>

            <x-form.text label="settings.label.spotify-url"
                wire:model.defer="settings.site_spotify_url"/>

            <x-form.text label="settings.label.tiktok-url"
                wire:model.defer="settings.site_tiktok_url"/>
        </x-form.group>
    </x-form>
</div>