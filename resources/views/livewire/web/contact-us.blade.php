<main class="min-h-screen">
@if ($thank)
    <div class="max-w-screen-sm mx-auto py-20 flex flex-col gap-6">
        <div class="text-5xl font-bold">
            {{ __('Thank You') }}
        </div>
        
        <x-alert type="success">
            {{ __('Your enquiry has been submitted successfully.') }}
        </x-alert>
    
        <div>
            <x-button icon="back" color="theme" 
                href="/" 
                label="common.label.back"/>
        </div>
    </div>
@else
    <div class="max-w-screen-lg mx-auto py-10 px-4 w-full">
        <div class="flex flex-col md:flex-row gap-10 ">
            <div class="md:w-1/3 flex flex-col gap-4">
                <div class="text-xl font-bold">
                    {{ tr('web.contact.heading.information') }}
                </div>

                @foreach (collect([
                    ['icon' => 'location', 'value' => settings('contact_address')],
                    ['icon' => 'phone', 'value' => settings('contact_phone')],
                    ['icon' => 'envelope', 'value' => settings('contact_email')],
                ])->filter(fn($val) => !empty(data_get($val, 'value'))) as $item)
                    <div class="flex gap-2">
                        <div class="shrink-0 text-gray-400 py-0.5">
                            <x-icon :name="data_get($item, 'icon')"/>
                        </div>
                        {{ data_get($item, 'value') }}
                    </div>
                @endforeach
            </div>

            <div class="md:w-2/3 flex flex-col gap-6">
                <div class="text-3xl font-bold">
                    {{ tr('web.contact.heading.message') }}
                </div>

                <x-form recaptcha="contact_us">
                    <x-form.group>
                        <x-form.text label="web.contact.label.name"
                            wire:model.defer="enquiry.name"/>
                        <x-form.phone label="web.contact.label.phone"
                            wire:model.defer="enquiry.phone"/>
                        <x-form.email label="web.contact.label.email"
                            wire:model.defer="enquiry.email"/>
                        <x-form.textarea label="web.contact.label.message"
                            wire:model.defer="enquiry.message"/>
                    </x-form.group>
                    
                    <x-slot:foot>
                        <x-button.submit color="theme" md block
                            label="web.contact.button.send"/>
                    </x-slot:foot>
                </x-form>
            </div>
        </div>

        @if ($url = settings('site_contact_map'))
            <iframe
                class="w-full bg-gray-200"
                height="450"
                loading="lazy"
                allowfullscreen
                src="{{ $url }}">
            </iframe>
        @endif
    </div>
@endif
</main>
