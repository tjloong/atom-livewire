<div class="max-w-screen-sm mx-auto py-20 px-4">
    <div class="text-7xl font-bold mb-6">
        Contact Us
    </div>

    <div>
        @if ($isSent)
            <x-alert type="success">
                You enquiry is successfully sent.
            </x-alert>
    
            <a href="/" class="text-sm flex items-center mt-4">
                <x-icon name="left-arrow-alt"/> Back to home page
            </a>
        @else
            <form wire:submit.prevent="save">
                <x-input.text wire:model.defer="enquiry.name" required>
                    Your Name
                </x-input.text>
        
                <x-input.phone wire:model.defer="enquiry.phone" required>
                    Contact Number
                </x-input.text>
        
                <x-input.email wire:model.defer="enquiry.email" required>
                    Contact Email
                </x-input.text>
        
                <x-input.textarea wire:model.defer="enquiry.message" required>
                    Message
                </x-input.textarea>
        
                <x-button type="submit" size="md" class="w-full">
                    Send Enquiry
                </x-button>
            </form>
        @endif
    </div>
</div>