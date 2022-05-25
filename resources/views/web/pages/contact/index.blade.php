<main class="min-h-screen flex flex-col">
    <div class="flex-grow">
        <div class="{{ $this->contact ? 'max-w-screen-lg' : 'max-w-screen-md w-full' }} mx-auto py-20 px-6">
            <div class="grid gap-10 {{ $this->contact ? 'md:grid-cols-12' : '' }}">
                @if ($this->contact)
                    <div class="md:col-span-4">
                        <div class="grid gap-4">
                            <div class="text-xl font-bold">Contact Information</div>
        
                            @if ($address = $this->contact['address'] ?? null)
                                <div>{{ $address }}</div>
                            @endif
        
                            @if ($phone = $this->contact['phone'] ?? null)
                                <div class="flex items-center gap-2">
                                    <x-icon name="phone" size="xs" class="text-gray-400"/> {{ $phone }}
                                </div>
                            @endif
        
                            @if ($email = $this->contact['email'] ?? null)
                                <div class="flex items-center gap-2">
                                    <x-icon name="envelope" size="xs" class="text-gray-400"/> {{ $email }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
    
                <div class="{{ $this->contact ? 'md:col-span-8' : '' }}">
                    <div class="text-3xl font-bold mb-6">
                        Send us a message
                    </div>
    
                    <form class="grid gap-6" wire:submit.prevent="submit">
                        <x-form.text 
                            label="Your Name"
                            wire:model.defer="enquiry.name" 
                            :error="$errors->first('enquiry.name')"
                            required
                        />
                
                        <x-form.phone 
                            label="Contact Number"
                            wire:model.defer="enquiry.phone" 
                            :error="$errors->first('enquiry.phone')" 
                            required
                        />
                
                        <x-form.email
                            label="Contact Email"
                            wire:model.defer="enquiry.email" 
                            :error="$errors->first('enquiry.email')"
                            required
                        />
                
                        <x-form.textarea
                            label="Message"
                            wire:model.defer="enquiry.message" 
                            :error="$errors->first('enquiry.message')"
                            required
                        />

                        <x-button type="submit" size="md" icon="paper-plane" block label="Send Enquiry"/>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($url = $this->contact['gmap_url'] ?? null)
        <iframe
            class="w-full bg-gray-200"
            height="450"
            loading="lazy"
            allowfullscreen
            src="{{ $url }}">
        </iframe>
    @endif
</main>
