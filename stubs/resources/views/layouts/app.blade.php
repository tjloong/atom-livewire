@extends('atom::layout')

@push('scripts')
    <script src="{{ mix('js/app.js') }}" defer></script>
@endpush

@push('vendors')
    <x-script.vendor/>
@endpush

@section('content')
    <x-admin-panel>
        <x-slot:links>
            <a href="/" class="text-gray-800">Go To Site</a>
        </x-slot:links>

        <x-slot:aside>
            <x-admin-panel.aside label="Dashboard" icon="chart-line" route="app.dashboard"/>    
            <x-admin-panel.aside label="Blogs" icon="edit-alt" route="app.blog.listing"/>    
            <x-admin-panel.aside label="Enquiries" icon="paper-plane" route="app.enquiry.listing"/>    
            <x-admin-panel.aside label="Accounts" icon="user-plus" route="app.account.listing"/>    
            <x-admin-panel.aside label="Support Tickets" icon="buoy" route="ticket.listing"/>    
            <x-admin-panel.aside label="Settings" icon="cog">
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

        {{ $slot }}
    </x-admin-panel>
@endsection