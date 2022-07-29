@extends('atom::layout', ['analytics' => true])

@section('content')
    <x-popup/>
    <x-loader/>

    <div class="min-h-screen bg-gray-100">
        <main class="max-w-screen-xl mx-auto pb-10 md:py-10">
            <x-builder.navbar logo-class="h-[40px]" align="right" :show-auth="false">
                @notroute('onboarding.completed')
                    <a href="{{ route('app.home') }}" class="flex items-center gap-1">
                        <x-icon name="left-arrow-alt" size="20px"/>
                        I'll do this later
                    </a>
                @endnotroute
            </x-builder.navbar>

            <div class="grid gap-6 p-4">
                @notroute('onboarding.completed')
                    <div class="grid gap-1">
                        <h1 class="text-xl font-bold">Please spend a minute to complete the following</h1>
                        <div class="text-gray-500 font-medium">This will help us quickly setup your account</div>
                    </div>
                @endnotroute
    
                {{ $slot }}
            </div>
        </main>
    </div>
@endsection
