@extends('atom::layout')

@section('content')
    <main class="min-h-screen bg-gray-100 px-4 py-20">
        <div class="max-w-lg mx-auto grid gap-8">
            <div class="grid gap-2">
                @if ($action === 'sent')
                    <div class="text-3xl font-bold">
                        Verification Email Sent
                    </div>
        
                    <p>
                        A new verification link has been sent to {{ request()->user()->email }}.
                    </p>
                @elseif ($action === 'verified')
                    <div class="text-3xl font-bold">
                        Thank you for verifying your email
                    </div>

                    <p>
                        Your email address is verified.
                    </p>
                @endif
            </div>
        
            <div>
                <x-button href="{{ Route::has('app.home') ? route('app.home') : '/' }}">
                    Back to home
                </x-button>
            </div>
        </div>
    </main>    
@endsection

