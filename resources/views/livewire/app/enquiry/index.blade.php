<div x-init="@js($enquiryId) && $wire.emit('updateEnquiry', @js($enquiryId))" class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.enquiry:2" xl/>
    @livewire('app.enquiry.listing', key('listing'))
</div>