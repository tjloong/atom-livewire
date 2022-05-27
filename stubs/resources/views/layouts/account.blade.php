@extends('atom::layout')

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-script.alpine/>
    <x-notify/>
    <x-loader/>

    <x-navbar class="bg-white shadow py-2" class.logo="h-[40px]"/>

    <div class="min-h-screen bg-gray-100">
        <main class="max-w-screen-lg mx-auto py-10 px-6">
            <div class="grid gap-6">
                <x-page-header title="Account Settings"/>

                {{ $slot }}
            </div>
        </main>
    </div>
@endsection
