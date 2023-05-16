@extends('atom::layout', [
    'cdn' => [
        'sortable', 
        'chartjs', 
        'clipboard',
        'swiper',
        current_route([
            'app.blog.*', 
            'app.page.*', 
            'app.settings',
        ]) ? 'ckeditor' : null,
    ],
])

@section('content')
    {{-- Auth layout --}}
    @route(['login', 'register', 'password.*', 'verification.*'])
        <div class="min-h-screen relative bg-gray-100 px-4 py-12 md:py-20">
            <div class="max-w-md mx-auto grid gap-10">
                <a class="mx-auto" href="/">
                    <x-logo class="w-40"/>
                </a>
            
                {{ $slot }}
            </div>
        </div>

    {{-- Shareable layout --}}
    @elseroute(['shareable', 'shareable.*'])
        <div class="min-h-screen relative bg-gray-100">
            <main class="max-w-screen-xl mx-auto px-4 py-12">
                {{ $slot }}
            </main>
        </div>

    {{-- Onboarding layout --}}
    @elseroute(['app.onboarding', 'app.onboarding.*'])
        <div class="min-h-screen bg-gray-100 p-6">
            {{ $slot }}
        </div>
    
    {{-- App layout --}}
    @elseroute('app.*')
        <x-admin-panel>
            <x-slot:links>
                <x-link href="/" icon="globe" label="Go To Site" class="text-gray-800"/>
            </x-slot:links>

            <x-slot:aside>
                <x-admin-panel.aside label="Dashboard" route="app.dashboard"/>                
                <x-admin-panel.aside label="Blogs" route="app.blog.listing" can="blog.manage"/>
                <x-admin-panel.aside label="Enquiries" route="app.enquiry.listing" can="enquiry.manage"/>
                <x-admin-panel.aside label="Accounts" route="app.account.listing" can="account.manage"/>
                <x-admin-panel.aside label="Support Tickets" route="app.ticketing.listing" can="ticketing.manage"/>
            </x-slot:aside>

            <x-slot:asidefoot>
                <x-admin-panel.aside label="Settings" route="app.settings"/>
                <x-admin-panel.aside label="Preferences" route="app.preferences" can="preference.manage"/>
            </x-slot:asidefoot>

            {{ $slot }}
        </x-admin-panel>

    {{-- Web layout --}}
    @else
        <x-navbar>
            <x-slot:body class="justify-end">
                @module('blogs')
                    <x-navbar.item href="/blog" label="Blogs"/>
                @endmodule

                <x-navbar.item href="/contact-us?ref=landing" label="Contact Us"/>
            </x-slot:body>
        </x-navbar>
        
        {{ $slot }}

        <footer>
            <x-footer/>
        </footer>
    @endroute
@endsection