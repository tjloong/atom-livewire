@extends('atom::layout', ['noindex' => true, 'tracking' => false, 'vendors' => ['floating-ui']])

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-notify.alert/>
    <x-notify.toast/>
    <x-notify.confirm/>
    <x-fullscreen-loader/>

    <div class="min-h-screen bg-gray-100">
        <div class="max-w-screen-lg mx-auto">
            <x-builder.navbar logo-class="h-[40px]" back-to-app/>

            <main class="grid gap-6 p-4 md:px-4 md:py-10">
                <h1 class="text-2xl font-bold">Account Settings</h1>
                {{ $slot }}
            </main>
        </div>
    </div>
@endsection
