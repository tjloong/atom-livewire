<x-form.drawer id="enquiry-update" wire:close="$emit('setEnquiryId')">
@if ($enquiry)
    <x-slot:heading title="{!! $enquiry->name !!}"></x-slot:heading>
    <x-slot:buttons delete></x-slot:buttons>

    <div class="-m-4">
        <x-form.group>
            <x-box>
                <div class="flex flex-col divide-y">
                    <x-field label="atom::enquiry.label.name"
                        :value="$enquiry->name"/>

                    <x-field label="atom::enquiry.label.phone"
                        :value="$enquiry->phone"/>

                    <x-field label="atom::enquiry.label.email"
                        :value="$enquiry->email"/>
            
                    <div class="p-4">
                        <x-form.field label="atom::enquiry.label.message">
                            {!! nl2br($enquiry->message) !!}
                        </x-form.field>
                    </div>
                </div>
            </x-box>
        </x-form.group>

        <x-form.group>
            <x-form.textarea label="atom::enquiry.label.remark"
                wire:model.defer="enquiry.remark"/>

            <x-form.select.enum label="atom::enquiry.label.status" enum="enquiry.status"
                wire:model="inputs.status"/>
        </x-form.group>
    </div>
@endif
</x-form.drawer>