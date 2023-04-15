<main class="min-h-screen">
    <div class="max-w-screen-xl mx-auto py-10 px-4 w-full">
        <div class="flex flex-col md:flex-row gap-10 ">
            <div class="md:w-1/3 flex flex-col gap-4">
                <div class="text-xl font-bold">
                    {{ __('Contact Information') }}
                </div>

                @foreach (collect([
                    ['icon' => 'location', 'value' => settings('address')],
                    ['icon' => 'phone', 'value' => settings('phone')],
                    ['icon' => 'envelope', 'value' => settings('email')],
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
                    {{ __('Send us a message') }}
                </div>

                <x-form>
                    <x-form.group>
                        <x-form.text wire:model.defer="enquiry.name" label="Your Name"/>
                        <x-form.phone wire:model.defer="enquiry.phone" label="Contact Number"/>
                        <x-form.email wire:model.defer="enquiry.email" label="Contact Email"/>
                        <x-form.textarea wire:model.defer="enquiry.message" label="Message"/>
                    </x-form.group>
                    
                    <x-slot:foot>
                        <x-button.submit size="md" color="theme" label="Send Enquiry" block/>
                    </x-slot:foot>

                    <x-slot:error-alert></x-slot:error-alert>
                </x-form>
            </div>
        </div>
    </div>

    @if ($url = settings('gmap_url'))
        <iframe
            class="w-full bg-gray-200"
            height="450"
            loading="lazy"
            allowfullscreen
            src="{{ $url }}">
        </iframe>
    @endif
</main>
