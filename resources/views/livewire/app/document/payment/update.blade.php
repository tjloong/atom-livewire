<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="'Payment #'.$payment->number" back>
        <div class="flex items-center gap-3">
            <x-button icon="download" color="gray" 
                :label="[
                    'invoice' => 'Receipt',
                    'bill' => 'Payment Voucher',
                ][$payment->document->type]"
                wire:click="pdf"
            />
    
            @can('document-payment.delete')
                <x-button.delete inverted
                    title="Delete Payment"
                    message="This will delete the payment. Are you sure?"
                />
            @endcan
        </div>
    </x-page-header>

    @livewire(atom_lw('app.document.payment.form'), compact('payment'))
</div>