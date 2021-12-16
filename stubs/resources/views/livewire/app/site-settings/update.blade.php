<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Site Settings"/>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                <x-sidenav.item name="contact">Contact Information</x-sidenav.item>
                <x-sidenav.item name="tracking">Site Tracking</x-sidenav.item>
                <x-sidenav.item name="seo">Site SEO</x-sidenav.item>

                <x-sidenav.item label>System</x-sidenav.item>
                <x-sidenav.item name="email">Email Configurations</x-sidenav.item>
                <x-sidenav.item name="storage">Storage</x-sidenav.item>
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            <div>
                @if ($tab === 'seo')
                    @livewire('app.site-settings.form.seo', key($tab))
                @endif
            </div>

            <div>
                @if ($tab === 'tracking')
                    @livewire('app.site-settings.form.tracking', key($tab))
                @endif
            </div>

            <div>
                @if ($tab === 'contact')
                    @livewire('app.site-settings.form.contact', key($tab))
                @endif
            </div>

            <div>
                @if ($tab === 'email')
                    @livewire('app.site-settings.form.email', key($tab))
                @endif
            </div>

            <div>
                @if ($tab === 'storage')
                    @livewire('app.site-settings.form.storage', key($tab))
                @endif
            </div>
        </div>
    </div>
</div>
