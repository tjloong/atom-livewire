<x-form.drawer wire:close="close()">
@if ($enquiry)
    <x-slot:heading title="{!! $enquiry->name !!}"></x-slot:heading>
    <x-slot:buttons delete></x-slot:buttons>

    <x-group>
        <x-box>
            <div class="flex flex-col divide-y">
                <x-field label="app.label.name" :value="$enquiry->name"/>
                <x-field label="app.label.phone" :value="$enquiry->phone"/>
                <x-field label="app.label.email" :value="$enquiry->email"/>
        
                <div class="p-4">
                    <x-form.field label="app.label.message">
                        {!! nl2br($enquiry->message) !!}
                    </x-form.field>
                </div>
            </div>
        </x-box>
    </x-group>

    <x-group>
        <x-form.textarea wire:model.defer="enquiry.notes" label="app.label.remark"/>
        <x-form.select.enum wire:model.defer="inputs.status" label="app.label.status" enum="enquiry.status"/>
    </x-group>
@endif
</x-form.drawer>