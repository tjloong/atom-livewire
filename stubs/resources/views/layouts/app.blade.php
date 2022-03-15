@extends('atom::layout', ['noindex' => true, 'tracking' => false, 'vendors' => ['floating-ui']])

@push('scripts')
    <script src="{{ mix('js/app.js') }}" defer></script>
@endpush

@section('content')
    <x-admin-panel>
        <x-slot name="links">
            <a href="/" class="text-gray-800">Go To Site</a>
        </x-slot>

        <x-slot name="aside">
            <x-admin-panel aside icon="chart-line" route="app.dashboard">
                Dashboard
            </x-admin-panel>

            <x-admin-panel aside icon="edit-alt" route="app.blog.listing" can="blogs.view" :active="str()->is('app.blog.*', current_route())">
                Blogs
            </x-admin-panel>

            <x-admin-panel aside icon="paper-plane" route="app.enquiry.listing" can="enquiries.manage" :active="str()->is('app.enquiry.*', current_route())">
                Enquiries
            </x-admin-panel>

            <x-admin-panel aside icon="file" route="app.page.listing" can="pages.manage" :active="str()->is('app.page.*', current_route())">
                Pages
            </x-admin-panel>

            <x-admin-panel aside icon="food-menu" route="app.plan.listing" can="plan.manage" :active="str()->is('app.plan.*', current_route())">
                Plans
            </x-admin-panel>

            <x-admin-panel aside icon="user-plus" route="app.account.listing" can="account.manage" :active="str()->is('app.account.*', current_route())">
                Accounts
            </x-admin-panel>

            <x-admin-panel aside icon="buoy" route="ticket.listing" can="tickets.view" :active="str()->is('ticket.*', current_route())">
                Support Tickets
            </x-admin-panel>

            <x-admin-panel aside icon="cog">
                Settings

                <x-slot name="subitems">
                    <x-admin-panel aside route="app.role.listing" can="roles.manage" :active="str()->is('app.role.*', current_route())">
                        Roles
                    </x-admin-panel>

                    <x-admin-panel aside route="app.user.listing" can="users.manage" :active="str()->is('app.user.*', current_route())">
                        Users
                    </x-admin-panel>

                    <x-admin-panel aside route="app.team.listing" can="teams.manage" :active="str()->is('app.team.*', current_route())">
                        Teams
                    </x-admin-panel>

                    <x-admin-panel aside route="app.label.listing" can="labels.manage" :active="str()->is('app.label.*', current_route())">
                        Labels
                    </x-admin-panel>

                    <x-admin-panel aside route="app.files">
                        Files
                    </x-admin-panel>

                    <x-admin-panel aside route="app.site-settings">
                        Site Settings
                    </x-admin-panel>
                </x-slot>
            </x-admin-panel>
        </x-slot>

        {{ $slot }}
    </x-admin-panel>
@endsection