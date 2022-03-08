@extends('atom::layout', ['noindex' => true, 'vendors' => ['floating-ui']])

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-notify.alert/>
    <x-notify.toast/>
    <x-notify.confirm/>
    <x-fullscreen-loader/>

    <div class="min-h-screen bg-gray-100">
        <x-builder.navbar logo-class="h-[40px]" align="right" :show-auth="false">
            @notroute('onboarding.completed')
            <a href="{{ route('app.home') }}" class="text-sm flex items-center gap-1">
                <x-icon name="left-arrow-alt" size="20px"/>
                I'll do this later
            </a>
            @endnotroute
        </x-builder.navbar>

        <main class="max-w-screen-xl mx-auto px-4 grid gap-6 mt-10 pb-10">
            @notroute('onboarding.completed')
                <div class="grid gap-1">
                    <h1 class="text-xl font-bold">Please spend a minute to complete the following</h1>
                    <div class="text-gray-500 font-medium">This will help us quickly setup your account</div>
                </div>
            @endnotroute

            {{ $slot }}
        </main>
    </div>
@endsection
