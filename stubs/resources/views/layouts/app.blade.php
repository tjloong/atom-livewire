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
                <x-admin-panel.aside label="Dashboard" icon="chart-line" route="app.dashboard"/>    
                <x-admin-panel.aside label="Blogs" icon="feather-pointed" route="app.blog.listing"/>    
                <x-admin-panel.aside label="Enquiries" icon="paper-plane" route="app.enquiry.listing"/>    
                <x-admin-panel.aside label="Accounts" icon="user-plus" route="app.account.listing" :active="current_route('app.account.*') && !current_route('app.account.home')"/>
                
                <x-admin-panel.aside label="Settings" icon="gear">
                    <x-slot:subitems>
                        <x-admin-panel.aside label="Roles" route="app.role.listing"/>    
                        <x-admin-panel.aside label="Users" route="app.user.listing"/>    
                        <x-admin-panel.aside label="Teams" route="app.team.listing"/>    
                        <x-admin-panel.aside label="Labels" route="app.label.listing"/>    
                        <x-admin-panel.aside label="Plans" route="app.plan.listing"/>    
                        <x-admin-panel.aside label="Pages" route="app.page.listing"/>    
                        <x-admin-panel.aside label="Files" route="app.files"/>    
                        <x-admin-panel.aside label="Taxes" route="app.tax.listing"/>    
                        <x-admin-panel.aside label="Site Settings" route="app.site-settings"/>
                    </x-slot:subitems>
                </x-admin-panel.aside>
            </x-slot:aside>

            <x-slot:asidefoot>
                <x-admin-panel.aside label="My Account" icon="address-card" route="app.account.home" :active="current_route('app.account.home')"/>
                <x-admin-panel.aside label="Support Tickets" icon="life-ring" route="app.ticketing.listing"/>
            </x-slot:asidefoot>

            {{ $slot }}
        </x-admin-panel>

    {{-- Web layout --}}
    @else
        <x-navbar align="right">
            @module('blogs')
                <x-navbar.item href="/blog" label="Blogs"/>
            @endmodule

            <x-navbar.item href="/contact-us?ref=landing" label="Contact"/>
        </x-navbar>
        
        {{ $slot }}

        <footer>
            <x-footer/>
        </footer>
    @endroute
@endsection