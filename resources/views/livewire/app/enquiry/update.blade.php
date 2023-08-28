<x-form.drawer id="enquiry-update" wire:close="$emit('enquirySaved')">
@if ($enquiry)
    <x-slot:heading title="Enquiry"></x-slot:heading>
    <x-slot:buttons delete></x-slot:buttons>

    <div class="-m-5">
        <x-form.group>
            <x-box>
                <div class="flex flex-col divide-y">
                    <x-field label="Name" :value="$enquiry->name"/>
                    <x-field label="Phone" :value="$enquiry->phone"/>
                    <x-field label="Email" :value="$enquiry->email"/>
            
                    <div class="p-4">
                        <x-form.field label="Message">
                            {!! nl2br($enquiry->message) !!}
                        </x-form.field>
                    </div>
                </div>
            </x-box>
        </x-form.group>

        <x-form.group>
            <x-form.textarea wire:model.defer="enquiry.remark"/>
            <x-form.select.enum wire:model="inputs.status" enum="enquiry.status"/>
        </x-form.group>
    </div>
@endif
</x-form.drawer>