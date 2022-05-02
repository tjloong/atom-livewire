<div class="max-w-screen-lg mx-auto">
    @if ($this->isFullpage)
        <x-page-header :title="$this->title"/>
    @endif

    <x-table :total="$this->accountPayments->total()" :links="$this->accountPayments->links()">
        @if (!$this->isFullpage)
            <x-slot:header>{{ __('Payment History') }}</x-slot:header>
        @endif

        <x-slot:head>
            <x-table head sort="created_at">{{ __('Date') }}</x-table>
            <x-table head>{{ __('Number') }}</x-table>
            <x-table head>{{ __('Description') }}</x-table>
            <x-table head align="right">{{ __('Amount') }}</x-table>
            <x-table head/>
        </x-slot:head>
            
        <x-slot:body>
            @foreach ($this->accountPayments as $accountPayment)
                <x-table row>
                    <x-table cell>
                        {{ format_date($accountPayment->created_at) }}
                        <div class="font-medium text-gray-500">{{ format_date($accountPayment->created_at, 'time') }}</div>
                    </x-table>

                    <x-table cell>
                        <a href="{{ route('billing.account-payment.update', [$accountPayment->id]) }}">
                            {{ $accountPayment->number }}
                        </a>
                    </x-table>

                    <x-table cell>
                        {{ str($accountPayment->description)->limit(40) }}
                    </x-table>

                    <x-table cell class="text-right">
                        {{ currency($accountPayment->amount, $accountPayment->currency) }}
                    </x-table>

                    <x-table cell class="text-right">
                        <x-badge>{{ $accountPayment->status }}</x-badge>
                    </x-table>
                </x-table>
            @endforeach
        </x-slot:body>
    </x-table>
</div>
