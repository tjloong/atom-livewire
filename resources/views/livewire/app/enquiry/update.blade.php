<div class="max-w-screen-sm mx-auto">
    <x-page-header title="Enquiry" back>
        <x-button.delete inverted
            title="Delete Enquiry"
            message="Are you sure to delete this enquiry?"
        />
    </x-page-header>

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

        <x-form.group>
            <x-form.textarea wire:model.defer="enquiry.remark"/>
            <x-form.select wire:model="enquiry.status" :options="[
                ['value' => 'pending', 'label' => 'Pending'],
                ['value' => 'closed', 'label' => 'Closed'],
            ]"/>
        </x-form.group>
    </x-box>
</div>