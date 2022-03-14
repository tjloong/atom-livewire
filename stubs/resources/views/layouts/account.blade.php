@extends('atom::layout', ['noindex' => true, 'tracking' => false, 'vendors' => ['floating-ui']])

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-notify.alert/>
    <x-notify.toast/>
    <x-notify.confirm/>
    <x-fullscreen-loader/>

    <x-builder.navbar class="bg-white py-3 px-4 shadow" logo-class="max-w-[100px] max-h-[50px]"/>

    <div class="min-h-screen bg-gray-100 px-6">
        <div class="max-w-screen-lg mx-auto grid gap-6 py-10">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <h2 class="text-xl font-bold">Account Settings</h2>
                <a href="{{ Route::has('app.home') ? route('app.home') : route('home') }}" class="flex items-center gap-2 text-gray-500">
                    <x-icon name="left-arrow-alt" size="20px"/> Back to Home
                </a>
            </div>
                
            <div class="grid gap-6 md:grid-cols-12">
                <aside class="md:col-span-3">
                    <x-sidenav>
                        <x-sidenav item icon="lock" href="{{ route('account.authentication') }}">Authentication</x-sidenav>
                    </x-sidenav>
                </aside>

                <div class="md:col-span-9">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
@endsection
