<div class="flex flex-col gap-4">
    <div class="grid gap-2">
        <div class="text-3xl font-bold">
            {{ __('Thank you for verifying your email') }}
        </div>

        <p>
            {{ __('Your email address is verified.') }}
        </p>
    </div>

    <a href="{{ route('login') }}" class="flex items-center gap-2">
        <x-icon name="arrow-left"/> {{ __('Back to Home') }}
    </a>
</div>