<div class="max-w-screen-md">
    <atom:card>
        <atom:_form>
            <atom:_heading size="xl">@t('site-settings')</atom:_heading>

            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="settings.site_name" label="Site Name"/>
                <atom:_input wire:model.defer="settings.site_description" label="Site Description"/>
                <atom:_input wire:model.defer="settings.documentation_url" label="Documentation URL"/>
            </div>

            <atom:separator>Contact Us</atom:separator>
            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="settings.contact_name" label="Contact Name"/>
                <atom:_input wire:model.defer="settings.contact_phone" label="Contact Phone"/>
                <atom:_input wire:model.defer="settings.contact_email" label="Contact Email"/>
                <atom:_input wire:model.defer="settings.contact_address" label="Contact Address"/>
                <div class="col-span-2">
                    <atom:_textarea wire:model.defer="settings.contact_map" label="Google Map"/>
                </div>
            </div>

            <atom:separator>Whatsapp</atom:separator>
            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="settings.whatsapp_number" label="Whatsapp Number"/>
                <atom:_input wire:model.defer="settings.whatsapp_text" label="Whatsapp Text"/>
            </div>

            <atom:separator>Social Media URL</atom:separator>
            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="settings.facebook_url" label="Facebook URL"/>
                <atom:_input wire:model.defer="settings.instagram_url" label="Instagram URL"/>
                <atom:_input wire:model.defer="settings.twitter_url" label="Twitter URL"/>
                <atom:_input wire:model.defer="settings.linkedin_url" label="Linkedin URL"/>
                <atom:_input wire:model.defer="settings.youtube_url" label="Youtube URL"/>
                <atom:_input wire:model.defer="settings.spotify_url" label="Spotify URL"/>
                <atom:_input wire:model.defer="settings.tiktok_url" label="Tiktok URL"/>
            </div>

            <atom:separator>SEO Meta Tag</atom:separator>
            <div class="grid gap-6 md:grid-cols-2">
                <atom:_input wire:model.defer="settings.meta_title" label="Title"/>
                <atom:_input wire:model.defer="settings.meta_description" label="Description"/>
                <atom:_input wire:model.defer="settings.meta_image" label="Image"/>
            </div>

            <atom:_button action="submit">@t('save')</atom:_button>
        </atom:_form>
    </atom:card>
</div>
