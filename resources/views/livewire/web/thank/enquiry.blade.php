<div class="min-h-screen max-w-screen-sm mx-auto px-4 flex flex-col items-center justify-center gap-4">
    <div class="text-5xl font-bold">
        {{ __('Thank You') }}
    </div>
    
    <x-alert type="success">
        {{ __('Your enquiry has been submitted successfully.') }}
    </x-alert>

    <x-button inverted href="/" label="Back to Home"/>
</div>
