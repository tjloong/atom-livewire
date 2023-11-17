<div x-init="$wire.emit('updateEnquiry', @js($enquiryId))" class="max-w-screen-xl mx-auto">
    <x-heading title="enquiry.heading.enquiry" 2xl/>
    @livewire('app.enquiry.listing', key('listing'))
</div>