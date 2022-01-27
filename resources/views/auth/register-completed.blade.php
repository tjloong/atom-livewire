<div class="max-w-lg mx-auto grid gap-8">
    <a href="/" class="w-40">
        <x-atom-logo/>
    </a>

    <div>
        <div class="text-3xl font-bold mb-2">
            Thank you for signing up with us
        </div>

        <p>
            Your account is successfully created. We are so excited to have you as our newest friend!
        </p>
    </div>

    <div>
        @if (Route::has('dashboard'))
            <x-button href="{{ route('dashboard') }}" size="md">
                Go to Dashboard <x-icon name="chevron-right"/>
            </x-button>
        @else
            <x-button href="/" size="md">
                Back to Home <x-icon name="chevron-right"/>
            </x-button>
        @endif
    </div>
</div>
