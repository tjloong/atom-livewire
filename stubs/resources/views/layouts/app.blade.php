@extends('atom::layout', ['noindex' => true, 'tracking' => false])

@section('content')
    <x-admin-panel>
        <x-slot name="links">
            <a href="/" class="text-gray-800">Go To Site</a>
        </x-slot>

        <x-slot name="dropdown">
            <x-dropdown item icon="user" href="{{ route('user.account') }}">My Account</x-dropdown>
        </x-slot>

        <x-slot name="aside">
            <x-admin-panel aside icon="cog">
                Settings

                <x-slot name="subitems">
                    <x-admin-panel aside href="{{ route('user.account') }}">
                        My Account
                    </x-admin-panel>

                    <x-admin-panel aside href="{{ route('role.listing') }}" :active="Str::is('role.*', current_route())">
                        Roles
                    </x-admin-panel>

                    <x-admin-panel aside href="{{ route('user.listing') }}" :active="Str::is('user.*', current_route()) && current_route() !== 'user.account'">
                        Users
                    </x-admin-panel>

                    <x-admin-panel aside href="{{ route('team.listing') }}" :active="Str::is('team.*', current_route())">
                        Teams
                    </x-admin-panel>

                    <x-admin-panel aside href="{{ route('label.listing', ['blog-category']) }}" :active="Str::is('label.*', current_route())">
                        Labels
                    </x-admin-panel>

                    <x-admin-panel aside href="{{ route('file.listing') }}">
                        Files
                    </x-admin-panel>

                    <x-admin-panel aside href="{{ route('site-settings.update', ['contact']) }}">
                        Site Settings
                    </x-admin-panel>
                </x-slot>
            </x-admin-panel>
        </x-slot>

        {{ $slot }}
    </x-admin-panel>
@endsection