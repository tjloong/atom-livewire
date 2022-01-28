<main class="min-h-screen">
    <div class="max-w-screen-sm mx-auto py-20 px-4 grid gap-6">
        <div class="text-5xl font-bold">Contact Us</div>
    
        <div>
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
        </div>
    </div>
</main>
