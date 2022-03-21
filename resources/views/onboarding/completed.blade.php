<div class="grid gap-10">
    <div class="grid gap-1">
        <div class="text-3xl font-bold">
            {{ __('You account setup is completed. Thank you for signing up with us') }}
        </div>
    
        <div class="text-gray-500 text-lg font-medium">
            {{ __('We are so excited to have you as our newest friend!') }}
        </div>
    </div>

    <div>
        <x-button href="{{ Route::has('app.home') ? route('app.home') : route('page') }}" size="md" icon="home-alt">
            {{ __('Back to Home') }}
        </x-button>
    </div>
</div>
