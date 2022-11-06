<div class="w-full">
    @if ($fullpage)
        <x-page-header :title="$this->title"/>
    @endif

    <x-table>
        <x-slot:header>
            @if (!$fullpage) <x-table.header label="Payment History"/> @endif
            <x-table.searchbar :total="$this->accountPayments->total()"/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Date" sort="created_at"/>
            <x-table.th label="Receipt Number"/>
            <x-table.th/>
            <x-table.th label="Amount" class="text-right"/>
        </x-slot:thead>
            
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
    </x-table>

    {!! $this->accountPayments->links() !!}
</div>
