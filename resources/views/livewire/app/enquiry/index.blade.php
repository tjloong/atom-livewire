<div
    x-data="{ enquiryId: @js($enquiryId) }"
    x-init="enquiryId && $wire.emit('updateEnquiry', enquiryId)"
    class="max-w-screen-xl mx-auto">
    <x-heading title="atom::enquiry.heading.enquiry" 2xl/>

    @livewire('app.enquiry.listing', key('listing'))
    @livewire('app.enquiry.update', key('update'))
</div>