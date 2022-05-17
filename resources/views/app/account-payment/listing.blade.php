<div class="max-w-screen-lg mx-auto">
    @if ($this->isFullpage)
        <x-page-header :title="$this->title"/>
    @endif

    <x-table :total="$this->accountPayments->total()" :links="$this->accountPayments->links()">
        @if (!$this->isFullpage)
            <x-slot:header>{{ __('Payment History') }}</x-slot:header>
        @endif

        <x-slot:head>
            <x-table.th label="Date" sort="created_at"/>
            <x-table.th label="Receipt Number"/>
            <x-table.th label="Description"/>
            <x-table.th label="Amount" class="text-right"/>
            <x-table.th/>
        </x-slot:head>
            
        <x-slot:body>
            @foreach ($this->accountPayments as $accountPayment)
                <x-table.tr>
                    <x-table.td :date="$accountPayment->created_at"/>
                    <x-table.td :href="route('billing.account-payment.update', [$accountPayment->id])" :label="$accountPayment->number"/>
                    <x-table.td :label="str($accountPayment->description)->limit(40)"/>
                    <x-table.td :amount="$accountPayment->amount" :currency="$accountPayment->currency" class="text-right"/>
                    <x-table.td :status="$accountPayment->status" class="text-right"/>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>
