<x-page wire:close="$emit('closeEnquiry')">
@if ($enquiry)
    <x-slot:buttons>
        <x-button action="delete" invert/>
    </x-slot:buttons>

    <x-form>
        <x-slot:title title="{!! $enquiry->name !!}"></x-slot:title>

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
            <x-select wire:model.defer="inputs.status" label="app.label.status" options="enum.enquiry-status"/>
        </div>
    </x-form>
@endif
</x-page>