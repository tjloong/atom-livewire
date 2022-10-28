<main class="min-h-screen">
    <div class="max-w-screen-sm mx-auto py-20 px-4 grid gap-6">
        <div class="text-5xl font-bold">
            {{ __('Thank You') }}
        </div>
        
        <div class="grid gap-4">
            <x-alert type="success">
                {{ __('Your enquiry has been submitted successfully.') }}
            </x-alert>
    
            <a href="/" class="flex items-center gap-2">
                <x-icon name="arrow-left"/> {{ __('Back to Home') }}
            </a>
        </div>
    </div>
</main>
