@extends('atom::layout', ['noindex' => true, 'tracking' => false, 'vendors' => ['floating-ui']])

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-notify.alert/>
    <x-notify.toast/>
    <x-notify.confirm/>
    <x-fullscreen-loader/>

    <x-builder.navbar class="bg-white shadow py-2" class.logo="h-[40px]"/>

    <div class="min-h-screen bg-gray-100">
        <main class="max-w-screen-lg mx-auto py-10 px-6">
            <div class="grid gap-6">
                <h1 class="text-2xl font-bold">Account Settings</h1>

                {{ $slot }}
            </div>
        </main>
    </div>
@endsection
