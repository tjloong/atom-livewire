<div class="max-w-lg mx-auto">
    <x-page-header title="Enquiry" back>
        <x-button color="red" icon="trash" inverted x-on:click="$dispatch('confirm', {
            title: 'Delete Enquiry',
            message: 'Are you sure to delete this enquiry?',
            type: 'error',
            onConfirmed: () => $wire.delete(),
        })">
            Delete
        </x-button>
    </x-page-header>

    <form wire:submit.prevent="save">
        <x-box>
            <div class="p-5">
                <x-input.field>
                    <x-slot name="label">Name</x-slot>
                    {{ $enquiry->name }}
                </x-input.field>

                <x-input.field>
                    <x-slot name="label">Phone</x-slot>
                    {{ $enquiry->phone }}
                </x-input.field>

                <x-input.field>
                    <x-slot name="label">Email</x-slot>
                    {{ $enquiry->email }}
                </x-input.field>

                <x-input.field>
                    <x-slot name="label">Message</x-slot>
                    {!! nl2br($enquiry->message) !!}
                </x-input.field>

                <x-input.textarea wire:model.defer="enquiry.remark">
                    Remark
                </x-input.textarea>

                <x-input.select wire:model.defer="enquiry.status" :options="[
                    ['value' => 'pending', 'label' => 'Pending'],
                    ['value' => 'closed', 'label' => 'Closed'],
                ]">
                    Status
                </x-input.select>
            </div>

            <x-slot name="buttons">
                <x-button type="submit" color="green" icon="check">
                    Save Enquiry
                </x-button>
            </x-slot>
        </x-box>
    </form>
</div>