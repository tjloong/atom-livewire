<main class="min-h-screen">
@if ($thank)
    <div class="max-w-screen-sm mx-auto py-20 space-y-6">
        <div class="text-5xl font-bold">
            @t('thank-you')
        </div>

        <atom:inform variant="success">
            @t('your-enquiry-submitted-successfully')
        </atom:inform>

        <atom:_button action="back" variant="primary" href="/">
            @t('back')
        </atom:_button>
    </div>
@else
    <div class="max-w-screen-lg mx-auto py-20 px-4 w-full">
        <div class="flex flex-col md:flex-row gap-10 ">
            <div class="md:w-1/3 flex flex-col gap-4">
                <div class="text-xl font-bold">
                    @t('contact-information')
                </div>

                @foreach (collect([
                    ['icon' => 'location', 'value' => settings('contact_address')],
                    ['icon' => 'phone', 'value' => settings('contact_phone')],
                    ['icon' => 'at', 'value' => settings('contact_email')],
                ])->filter(fn($val) => !empty(get($val, 'value'))) as $item)
                    <div class="flex gap-2">
                        <div class="shrink-0 text-gray-400 py-0.5">
                            <atom:icon :name="get($item, 'icon')"/>
                        </div>
                        @e(get($item, 'value'))
                    </div>
                @endforeach
            </div>

            <div class="md:w-2/3 flex flex-col gap-6">
                <div class="text-3xl font-bold">
                    @t('send-us-a-message')
                </div>

                <atom:_form x-recaptcha:submit.contact_us.prevent="() => $wire.submit()">
                    <atom:_input wire:model.defer="enquiry.name" label="your-name"/>
                    <atom:_input type="tel" wire:model.defer="enquiry.phone" label="contact-number"/>
                    <atom:_input type="email" wire:model.defer="enquiry.email" label="contact-email"/>
                    <atom:_textarea wire:model.defer="enquiry.message" label="your-message"/>
                    <atom:_button action="submit" variant="primary">
                        @t('send-enquiry')
                    </atom:_button>
                </atom:_form>
            </div>
        </div>
    </div>

    @if ($map = settings('contact_map'))
        {!! $map !!}
    @endif
@endif
</main>
