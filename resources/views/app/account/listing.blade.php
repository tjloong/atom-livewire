<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title"/>

    <x-table :total="$accounts->total()" :links="$accounts->links()" export>
        <x-slot name="head">
            <x-table head sort="created_at">Join Date</x-table>
            <x-table head sort="name">Name</x-table>
            <x-table head>Contact</x-table>
            
            @module('plans')
                <x-table head align="right">Plans</x-table>
            @endmodule

            <x-table head/>
        </x-slot>

        <x-slot name="body">
            @foreach ($accounts as $account)
                <x-table row>
                    <x-table cell>
                        {{ format_date($account->created_at) }}
                        <div class="font-medium text-gray-500">{{ format_date($account->created_at, 'time') }}</div>
                    </x-table>

                    <x-table cell>
                        <a href="{{ route('app.account.update', [$account->id]) }}">
                            {{ $account->name }}
                        </a>
                    </x-table>

                    <x-table cell>
                        @foreach (array_filter([$account->email, $account->phone ?? null]) as $contact)
                            <div>{{ $contact }}</div>
                        @endforeach
                    </x-table>

                    @module('plans')
                        <x-table cell class="text-right">
                            @if ($account->subscriptions->count())
                                <div class="flex flex-wrap items-center justify-end gap-1">
                                    @foreach ($account->subscriptions as $subscription)
                                        <span class="py-0.5 px-2 bg-gray-100 rounded text-sm uppercase">
                                            {{ str($subscription->planPrice->plan->name)->limit(15) }}
                                        </span>
                                    @endforeach
                                </div>                                
                            @else
                                --                             
                            @endif
                        </x-table>
                    @endmodule

                    <x-table cell class="text-right">
                        <x-badge>{{ $account->status }}</x-badge>
                    </x-table>
                </x-table>
            @endforeach
        </x-slot>
    </x-table>
</div>