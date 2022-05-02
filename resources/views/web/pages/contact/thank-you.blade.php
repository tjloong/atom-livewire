<main class="min-h-screen">
    <div class="max-w-screen-sm mx-auto py-20 px-4 grid gap-6">
        <div class="text-5xl font-bold">Thank You</div>
        <div>
            <x-alert type="success">
                {{ __('Your enquiry has been submitted successfully.') }}
            </x-alert>
    
            <a href="/" class="flex items-center mt-4">
                <x-icon name="left-arrow-alt"/> {{ __('Back to Home') }}
            </a>
        </div>
    </div>
</main>
