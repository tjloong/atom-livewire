<div class="flex flex-col gap-4">
    <div class="grid gap-2">
        @if ($action === 'sent')
            <div class="text-3xl font-bold">
                {{ __('Verification Email Sent') }}
            </div>

            <p>
                {{ __('A new verification link has been sent to :email', ['email' => request()->user()->email]) }}
            </p>
        @elseif ($action === 'verified')
            <div class="text-3xl font-bold">
                {{ __('Thank you for verifying your email') }}
            </div>

            <p>
                {{ __('Your email address is verified.') }}
            </p>
        @endif
    </div>

    <a href="{{ route('login') }}" class="flex items-center gap-2">
        <x-icon name="arrow-left"/> {{ __('Back to Home') }}
    </a>
</div>