<main class="min-h-screen flex flex-col">
    <div class="flex-grow flex flex-col items-center justify-center">
        <div class="max-w-screen-lg mx-auto py-20 px-6">
            <div class="grid gap-10 md:grid-cols-12">
                <div class="md:col-span-4">
                    <div class="grid gap-4">
                        <div class="text-xl font-bold">Contact Information</div>
    
                        @if ($address = $contact['address'])
                            <div>{{ $address }}</div>
                        @endif
    
                        @if ($phone = $contact['phone'])
                            <div class="flex items-center gap-2">
                                <x-icon name="phone" class="text-gray-400"/> {{ $phone }}
                            </div>
                        @endif
    
                        @if ($email = $contact['email'])
                            <div class="flex items-center gap-2">
                                <x-icon name="envelope" class="text-gray-400"/> {{ $email }}
                            </div>
                        @endif
                    </div>
                </div>
    
                <div class="md:col-span-8">
                    <div class="text-3xl font-bold mb-6">
                        Send us a message
                    </div>
    
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
    
        </div>
    </div>

    @if ($address = $contact['address'])
        @if ($gmapApi)
            <iframe
                class="w-full bg-gray-200"
                height="450"
                loading="lazy"
                allowfullscreen
                src="https://www.google.com/maps/embed/v1/place?key={{ $gmapApi }}&q={{ urlencode($address) }}">
            </iframe>
        @endif
    @endif
</main>
