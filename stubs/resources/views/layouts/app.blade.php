@extends('atom::layout', ['noindex' => true, 'tracking' => false])

@push('scripts')
    <script src="https://unpkg.com/@floating-ui/core@0.1.2/dist/floating-ui.core.min.js"></script>
    <script src="https://unpkg.com/@floating-ui/dom@0.1.2/dist/floating-ui.dom.min.js"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
@endpush

@section('content')
    <x-admin-panel>
        <x-slot name="links">
            <a href="/" class="text-gray-800">Go To Site</a>
        </x-slot>

        <x-slot name="dropdown">
            <x-dropdown item icon="user" route="user.account">My Account</x-dropdown>
        </x-slot>

        <x-slot name="aside">
            <x-admin-panel aside icon="edit-alt" route="blog.listing" can="blogs.view" :active="str()->is('blog.*', current_route())">
                Blogs
            </x-admin-panel>

            <x-admin-panel aside icon="paper-plane" route="enquiry.listing" can="enquiries.manage" :active="str()->is('enquiry.*', current_route())">
                Enquiries
            </x-admin-panel>

            <x-admin-panel aside icon="file" route="page.listing" can="pages.manage" :active="str()->is('page.*', current_route())">
                Pages
            </x-admin-panel>

            <x-admin-panel aside icon="buoy" route="ticket.listing" can="tickets.view" :active="str()->is('ticket.*', current_route())">
                Support Tickets
            </x-admin-panel>

            <x-admin-panel aside icon="cog">
                Settings

                <x-slot name="subitems">
                    <x-admin-panel aside route="user.account">
                        My Account
                    </x-admin-panel>

                    <x-admin-panel aside route="role.listing" can="roles.manage" :active="str()->is('role.*', current_route())">
                        Roles
                    </x-admin-panel>

                    <x-admin-panel aside route="user.listing" can="users.manage" :active="str()->is('user.*', current_route()) && current_route() !== 'user.account'">
                        Users
                    </x-admin-panel>

                    <x-admin-panel aside route="team.listing" can="teams.manage" :active="str()->is('team.*', current_route())">
                        Teams
                    </x-admin-panel>

                    <x-admin-panel aside route="label.listing" can="labels.manage" :active="str()->is('label.*', current_route())">
                        Labels
                    </x-admin-panel>

                    <x-admin-panel aside route="files">
                        Files
                    </x-admin-panel>

                    <x-admin-panel aside route="site-settings">
                        Site Settings
                    </x-admin-panel>
                </x-slot>
            </x-admin-panel>
        </x-slot>

        {{ $slot }}
    </x-admin-panel>
@endsection