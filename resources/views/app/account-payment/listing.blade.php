<div class="w-full">
    @if ($fullpage)
        <x-page-header :title="$this->title"/>
    @endif

    <x-table :total="$this->accountPayments->total()" :links="$this->accountPayments->links()">
        @if (!$fullpage)
            <x-slot:header>{{ __('Payment History') }}</x-slot:header>
        @endif

        <x-slot:head>
            <x-table.th label="Date" sort="created_at"/>
            <x-table.th label="Receipt Number"/>
            <x-table.th/>
            <x-table.th label="Amount" class="text-right"/>
        </x-slot:head>
            
        <x-slot:body>
            @foreach ($this->accountPayments as $accountPayment)
                <x-table.tr>
                    <x-table.td :date="$accountPayment->created_at"/>
                    
                    <x-table.td>
                        <a href="{{ route('app.account-payment.update', [$accountPayment->id]) }}">
                            {{ $accountPayment->number }}
                        </a>
                        <div class="text-gray-500 grid">
                            <div class="truncate">
                                {{ $accountPayment->description }}
                            </div>
                        </div>
                    </x-table.td>

                    <x-table.td class="text-right">
                        @if ($fullpage)
                            <x-badge :label="$accountPayment->status"/>
                        @elseif ($accountPayment->is_auto_billing)
                            <x-badge label="auto"/>
                        @endif
                    </x-table.td>

                    <x-table.td :amount="$accountPayment->amount" :currency="$accountPayment->currency" class="text-right"/>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>
