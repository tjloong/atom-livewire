<x-form id="enquiry-update" header="Enquiry" drawer>
@if ($enquiry)
    <x-slot:buttons>
        <x-button.submit size="sm"/>
        <x-button.delete size="sm" :label="false" inverted
            title="Delete Enquiry"
            message="Are you sure to delete this enquiry?"
        />
    </x-slot:buttons>

    <div class="p-6 flex flex-col gap-4">
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
        
        <x-form.textarea wire:model.defer="enquiry.remark"/>
        <x-form.select.enum wire:model="inputs.status" enum="enquiry.status"/>
    </div>
@endif
</x-form>