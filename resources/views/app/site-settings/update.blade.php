<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Site Settings"/>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav>
                @if (config('atom.features.site_settings') === 'cms')
                    <x-sidenav item href="{{ route('site-settings', ['contact']) }}">Contact Information</x-sidenav>
                    <x-sidenav item href="{{ route('site-settings', ['tracking']) }}">Site Tracking</x-sidenav>
                    <x-sidenav item href="{{ route('site-settings', ['seo']) }}">Site SEO</x-sidenav>
                    <x-sidenav item href="{{ route('site-settings', ['social-media']) }}">Social Media</x-sidenav>
                @endif

                <x-sidenav item label>System</x-sidenav>
                <x-sidenav item href="{{ route('site-settings', ['email']) }}">Email Configurations</x-sidenav>
                <x-sidenav item href="{{ route('site-settings', ['storage']) }}">Storage</x-sidenav>
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @livewire('atom.site-settings.form.' . $category, key($category))
        </div>
    </div>
</div>
