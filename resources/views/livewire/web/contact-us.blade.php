<main class="min-h-screen">
@if ($thank)
    <div class="max-w-screen-sm mx-auto py-20 flex flex-col gap-6">
        <div class="text-5xl font-bold">
            {{ str()->apa(tr('app.label.thank-you')) }}
        </div>

        <x-inform type="success" message="app.label.your-enquiry-submitted-successfully"/>

        <div>
            <x-button action="back" color="theme" href="/"/>
        </div>
    </div>
@else
    <div class="max-w-screen-lg mx-auto py-20 px-4 w-full">
        <div class="flex flex-col md:flex-row gap-10 ">
            <div class="md:w-1/3 flex flex-col gap-4">
                <div class="text-xl font-bold">
                    {{ str()->apa(tr('app.label.contact-information')) }}
                </div>

                @foreach (collect([
                    ['icon' => 'location', 'value' => settings('contact_address')],
                    ['icon' => 'phone', 'value' => settings('contact_phone')],
                    ['icon' => 'envelope', 'value' => settings('contact_email')],
                ])->filter(fn($val) => !empty(get($val, 'value'))) as $item)
                    <div class="flex gap-2">
                        <div class="shrink-0 text-gray-400 py-0.5">
                            <x-icon :name="get($item, 'icon')"/>
                        </div>
                        {{ get($item, 'value') }}
                    </div>
                @endforeach
            </div>

            <div class="md:w-2/3 flex flex-col gap-6">
                <div class="text-3xl font-bold">
                    {{ str()->apa(tr('app.label.send-us-a-message')) }}
                </div>

                <x-form x-recaptcha:submit.contact-us.prevent="() => $wire.submit()">
                    <x-inputs>
                        <x-input wire:model.defer="enquiry.name" label="app.label.your-name"/>
                        <x-phone wire:model.defer="enquiry.phone" label="app.label.contact-number"/>
                        <x-input type="email" wire:model.defer="enquiry.email" label="app.label.contact-email"/>
                        <x-textarea wire:model.defer="enquiry.message" label="app.label.your-message"/>
                    </x-inputs>

                    <x-slot:foot>
                        <x-button action="submit" label="app.label.send-enquiry" color="theme" lg block/>
                    </x-slot:foot>
                </x-form>
            </div>
        </div>
    </div>

    @if ($map = settings('contact_map'))
        {!! $map !!}
    @endif
@endif
</main>
