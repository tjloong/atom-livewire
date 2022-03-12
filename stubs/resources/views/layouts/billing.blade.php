@extends('atom::layout', ['noindex' => true, 'tracking' => false])

@push('scripts')
    <script src="{{ mix('js/web.js') }}" defer></script>
@endpush

@section('content')
    <x-notify.alert/>
    <x-notify.toast/>
    <x-notify.confirm/>
    <x-fullscreen-loader/>

    <div class="bg-gray-100 p-5 min-h-screen">
        <main class="max-w-screen-xl mx-auto">
            <div class="grid gap-6 md:grid-cols-12 md:py-5">
                <div class="md:col-span-3">
                    <div class="grid gap-6">
                        <div class="h-[50px] w-[120px]">
                            <x-atom-logo/>
                        </div>
    
                        <h1 class="text-xl font-semibold">
                            Billing Management
                        </h1>
    
                        <div class="font-medium text-gray-500">
                            Signed in as 
                            <div class="text-gray-800 flex items-center gap-1">
                                <x-icon name="user-circle" size="20px"/>
                                {{ auth()->user()->name }}
                            </div>
                        </div>
    
                        @if (
                            !auth()->user()->account
                            || (enabled_module('accounts') && auth()->user()->account->subscriptions->count() > 0)
                        )
                            <a href="{{ app_route() }}" class="flex items-center gap-1 text-gray-500 font-medium">
                                <x-icon name="left-arrow-alt"/> Back to Home
                            </a>
                        @endif
                    </div>
                </div>
    
                <div class="md:col-span-9">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
@endsection