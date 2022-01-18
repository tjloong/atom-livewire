<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Site Settings"/>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav>
                <x-sidenav item href="{{ route('site-settings.update', ['contact']) }}">Contact Information</x-sidenav>
                <x-sidenav item href="{{ route('site-settings.update', ['tracking']) }}">Site Tracking</x-sidenav>
                <x-sidenav item href="{{ route('site-settings.update', ['seo']) }}">Site SEO</x-sidenav>
                <x-sidenav item href="{{ route('site-settings.update', ['social-media']) }}">Social Media</x-sidenav>

                <x-sidenav item label>System</x-sidenav>
                <x-sidenav item href="{{ route('site-settings.update', ['email']) }}">Email Configurations</x-sidenav>
                <x-sidenav item href="{{ route('site-settings.update', ['storage']) }}">Storage</x-sidenav>
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @livewire('app.site-settings.form.' . $category, key($category))
        </div>
    </div>
</div>
