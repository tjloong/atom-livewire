<x-form.drawer id="enquiry-update" wire:close="close()">
@if ($enquiry)
    <x-slot:heading title="{!! $enquiry->name !!}"></x-slot:heading>
    <x-slot:buttons delete></x-slot:buttons>

    <x-form.group>
        <x-box>
            <div class="flex flex-col divide-y">
                <x-field label="common.label.name" :value="$enquiry->name"/>
                <x-field label="common.label.phone" :value="$enquiry->phone"/>
                <x-field label="common.label.email" :value="$enquiry->email"/>
        
                <div class="p-4">
                    <x-form.field label="common.label.message">
                        {!! nl2br($enquiry->message) !!}
                    </x-form.field>
                </div>
            </div>
        </x-box>
    </x-form.group>

    <x-form.group>
        <x-form.textarea label="common.label.remark"
            wire:model.defer="enquiry.notes"/>

        <x-form.select.enum label="common.label.status" enum="enquiry.status"
            wire:model.defer="inputs.status"/>
    </x-form.group>
@endif
</x-form.drawer>