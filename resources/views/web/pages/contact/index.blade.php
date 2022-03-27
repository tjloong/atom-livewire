<main class="min-h-screen flex flex-col">
    <div class="flex-grow">
        <div class="{{ $contact ? 'max-w-screen-lg' : 'max-w-screen-md w-full' }} mx-auto py-20 px-6">
            <div class="grid gap-10 {{ $contact ? 'md:grid-cols-12' : '' }}">
                @if ($contact)
                    <div class="md:col-span-4">
                        <div class="grid gap-4">
                            <div class="text-xl font-bold">Contact Information</div>
        
                            @if ($address = $contact['address'] ?? null)
                                <div>{{ $address }}</div>
                            @endif
        
                            @if ($phone = $contact['phone'] ?? null)
                                <div class="flex items-center gap-2">
                                    <x-icon name="phone" size="xs" class="text-gray-400"/> {{ $phone }}
                                </div>
                            @endif
        
                            @if ($email = $contact['email'] ?? null)
                                <div class="flex items-center gap-2">
                                    <x-icon name="envelope" size="xs" class="text-gray-400"/> {{ $email }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
    
                <div class="{{ $contact ? 'md:col-span-8' : '' }}">
                    <div class="text-3xl font-bold mb-6">
                        Send us a message
                    </div>
    
                    <form wire:submit.prevent="submit">
                        <x-input.text wire:model.defer="enquiry.name" :error="$errors->first('enquiry.name')" required>
                            Your Name
                        </x-input.text>
                
                        <x-input.phone wire:model.defer="enquiry.phone" :error="$errors->first('enquiry.phone')" required>
                            Contact Number
                        </x-input.text>
                
                        <x-input.email wire:model.defer="enquiry.email" :error="$errors->first('enquiry.email')" required>
                            Contact Email
                        </x-input.text>
                
                        <x-input.textarea wire:model.defer="enquiry.message" :error="$errors->first('enquiry.message')" required>
                            Message
                        </x-input.textarea>
                
                        <x-button type="submit" size="md" icon="paper-plane" block>
                            Send Enquiry
                        </x-button>
                    </form>
                </div>
            </div>
    
        </div>
    </div>

    @if ($url = $contact['gmap_url'] ?? null)
        <iframe
            class="w-full bg-gray-200"
            height="450"
            loading="lazy"
            allowfullscreen
            src="{{ $url }}">
        </iframe>
    @endif
</main>
