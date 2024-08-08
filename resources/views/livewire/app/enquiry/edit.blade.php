<x-drawer submit wire:close="$emit('closeEnquiry')">
@if ($enquiry)
    <x-slot:heading title="{!! $enquiry->name !!}"></x-slot:heading>

    <x-slot:buttons>
        <x-button action="submit"/>
        <x-button action="delete" invert no-label/>
    </x-slot:buttons>

    <div class="p-5 flex flex-col gap-5">
        <x-fieldset>
            <x-field label="app.label.name" :value="$enquiry->name"/>
            <x-field label="app.label.phone" :value="$enquiry->phone"/>
            <x-field label="app.label.email" :value="$enquiry->email"/>
            <x-field label="app.label.message" block>
                {!! nl2br($enquiry->message) !!}
            </x-field>
        </x-fieldset>

        <x-textarea wire:model.defer="enquiry.notes" label="app.label.remark"/>
        <x-select wire:model.defer="inputs.status" label="app.label.status" options="enum.enquiry.status"/>
    </div>
@endif
</x-drawer>