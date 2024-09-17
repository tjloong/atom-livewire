<x-page wire:close="$emit('closeEnquiry')" class="max-w-screen-sm">
@if ($enquiry)
    <x-slot:buttons>
        <x-button action="delete" invert/>
    </x-slot:buttons>

    <x-form>
        <x-slot:title title="app.label.enquiry" class="bg-gray-100 border-b"></x-slot:title>

        <x-inputs>
            <x-fieldset>
                <x-field label="app.label.name" :value="$enquiry->name"/>
                <x-field label="app.label.phone" :value="$enquiry->phone"/>
                <x-field label="app.label.email" :value="$enquiry->email"/>
                <x-field label="app.label.message">
                    {!! nl2br($enquiry->message) !!}
                </x-field>
            </x-fieldset>

            <x-textarea wire:model.defer="enquiry.notes" label="app.label.remark"/>
            <x-select wire:model.defer="inputs.status" label="app.label.status" options="enum.enquiry-status"/>
        </x-inputs>
    </x-form>
@endif
</x-page>