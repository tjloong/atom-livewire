<div class="max-w-lg mx-auto">
    <x-page-header title="Enquiry" back>
        <x-button.delete inverted
            title="Delete Enquiry"
            message="Are you sure to delete this enquiry?"
        />
    </x-page-header>

    <x-form>
        <x-form.field label="Name">
            {{ $enquiry->name }}
        </x-form.field>

        <x-form.field label="Phone">
            {{ $enquiry->phone }}
        </x-form.field>

        <x-form.field label="Email">
            {{ $enquiry->email }}
        </x-form.field>

        <x-form.field label="Message">
            {!! nl2br($enquiry->message) !!}
        </x-form.field>

        <x-form.textarea 
            label="Remark"
            wire:model.defer="enquiry.remark"
        />

        <x-form.select 
            label="Status"
            wire:model="enquiry.status" 
            :options="[
                ['value' => 'pending', 'label' => 'Pending'],
                ['value' => 'closed', 'label' => 'Closed'],
            ]"
        />

        <x-slot:foot>
            <x-button.submit/>
        </x-slot:foot>
    </x-form>
</div>