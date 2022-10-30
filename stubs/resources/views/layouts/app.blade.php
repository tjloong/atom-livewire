@extends('atom::layout', [
    'cdn' => [
        'sortable', 
        'chartjs', 
        'clipboard',
        current_route([
            'app.blog.*', 
            'app.page.*', 
        ]) ? 'ckeditor' : null,
    ],
])

@section('content')
    {{-- Auth layout --}}
    @route(['login', 'register', 'password.*', 'verification.*'])
        <div class="min-h-screen relative bg-gray-100 px-4 py-12 md:py-20">
            {{ $slot }}
        </div>

    {{-- Shareable layout --}}
    @elseroute(['shareable', 'shareable.*'])
        <div class="min-h-screen relative bg-gray-100">
            <main class="max-w-screen-xl mx-auto px-4 py-12">
                {{ $slot }}
            </main>
        </div>

    {{-- Onboarding layout --}}
    @elseroute('app.onboarding.*')
        <div class="min-h-screen bg-gray-100 p-6">
            <main class="max-w-screen-xl mx-auto grid gap-10">
                <nav class="flex flex-wrap items-center justify-between gap-2">
                    <x-logo class="w-14"/>

                    @notroute('app.onboarding.completed')
                        <a href="{{ route('app.home') }}" class="flex items-center gap-1">
                            <x-icon name="left-arrow-alt" size="20px"/> {{ __('I\'ll do this later') }}
                        </a>
                    @endnotroute
                </nav>

                @notroute('app.onboarding.completed')
                    <div>
                        <h1 class="text-xl font-bold">
                            {{ __('Please spend a minute to complete the following') }}
                        </h1>
                        <div class="text-gray-500 font-medium">
                            {{ __('This will help us quickly setup your account') }}
                        </div>
                    </div>
                @endnotroute

                {{ $slot }}
            </main>
        </div>
    
    {{-- App layout --}}
    @elseroute('app.*')
        <x-admin-panel>
            <x-slot:links>
                <a href="/" class="text-gray-800">Go To Site</a>
            </x-slot:links>

            <x-slot:aside>
                <x-admin-panel.aside label="Dashboard" route="app.dashboard"/>    
                <x-admin-panel.aside label="Blogs" route="app.blog.listing"/>    
                <x-admin-panel.aside label="Enquiries" route="app.enquiry.listing"/>    
                <x-admin-panel.aside label="Accounts" route="app.account.listing"/>
                <x-admin-panel.aside label="Support Tickets" route="app.ticketing.listing"/>
            </x-slot:aside>

            <x-slot:asidefoot>
                <x-admin-panel.aside label="Settings" route="app.settings"/>
                <x-admin-panel.aside label="Preferences" route="app.preferences"/>
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