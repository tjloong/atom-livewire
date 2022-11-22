<div class="max-w-screen-sm mx-auto">
    <x-page-header title="Enquiry" back>
        <x-button.delete inverted
            title="Delete Enquiry"
            message="Are you sure to delete this enquiry?"
        />
    </x-page-header>

    <x-box>
        <div class="grid divide-y">
            <x-box.row label="Name">
                {{ $enquiry->name }}
            </x-box.row>
    
            <x-box.row label="Phone">
                {{ $enquiry->phone }}
            </x-box.row>
    
            <x-box.row label="Email">
                {{ $enquiry->email }}
            </x-box.row>
    
            <x-box.row label="Message">
                {!! nl2br($enquiry->message) !!}
            </x-box.row>
    
            <div class="p-4">
                <x-form.textarea 
                    label="Remark"
                    wire:model.defer="enquiry.remark"
                />
            </div>
    
            <div class="p-4">
                <x-form.select 
                    label="Status"
                    wire:model="enquiry.status" 
                    :options="[
                        ['value' => 'pending', 'label' => 'Pending'],
                        ['value' => 'closed', 'label' => 'Closed'],
                    ]"
                />
            </div>
        </div>

        <x-slot:foot>
            <x-button.submit type="button" wire:click="submit"/>
        </x-slot:foot>
    </x-box>
</div>