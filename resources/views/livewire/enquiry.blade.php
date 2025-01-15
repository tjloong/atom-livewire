<atom:_form x-recaptcha:submit.contact_us.prevent="() => $wire.submit()">
    <atom:_input wire:model.defer="enquiry.name" label="your-name"/>
    <atom:_input type="tel" wire:model.defer="enquiry.phone" label="contact-number"/>
    <atom:_input type="email" wire:model.defer="enquiry.email" label="contact-email"/>
    <atom:_textarea wire:model.defer="enquiry.message" label="your-message"/>
    <atom:_button action="submit" variant="primary">
        @t('send-enquiry')
    </atom:_button>
</atom:_form>
