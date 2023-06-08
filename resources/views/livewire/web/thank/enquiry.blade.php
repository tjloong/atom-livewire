<div class="max-w-screen-sm mx-auto px-4 grid gap-6">
    <div class="text-5xl font-bold">
        {{ __('Thank You') }}
    </div>
    
    <div class="grid gap-4">
        <x-alert type="success">
            {{ __('Your enquiry has been submitted successfully.') }}
        </x-alert>

        <div>
            <x-button inverted href="/" label="Back to Home"/>
        </div>
    </div>
</div>
