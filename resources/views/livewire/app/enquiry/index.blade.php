<div class="max-w-screen-xl mx-auto"
    x-init="$wire.get('enquiryId') && $wire.emit('updateEnquiry', $wire.get('enquiryId'))">
    <x-heading title="enquiry.heading.enquiry" 2xl/>
    @livewire('app.enquiry.listing', key('listing'))
</div>