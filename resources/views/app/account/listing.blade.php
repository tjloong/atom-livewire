<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title"/>

    <x-table :total="$this->accounts->total()" :links="$this->accounts->links()" export>
        <x-slot:head>
            <x-table.th sort="created_at">Join Date</x-table.th>
            <x-table.th sort="name">Name</x-table.th>
            <x-table.th>Contact</x-table.th>
            
            @module('plans')
                <x-table.th class="text-right">Plans</x-table.th>
            @endmodule

            <x-table.th/>
        </x-slot:head>

        <x-slot:body>
            @foreach ($this->accounts as $account)
                <x-table.tr>
                    <x-table.td :datetime="$account->created_at"/>
                    <x-table.td :label="$account->name" :href="route('app.account.update', [$account->id])"/>

                    <x-table.td>
                        @foreach (array_filter([$account->email, $account->phone ?? null]) as $contact)
                            <div>{{ $contact }}</div>
                        @endforeach
                    </x-table.td>

                    @module('plans')
                        <x-table.td class="text-right">
                            @if ($account->accountSubscriptions->count())
                                <div class="flex flex-wrap items-center justify-end gap-1">
                                    @foreach ($account->accountSubscriptions as $subscription)
                                        <span class="py-0.5 px-2 bg-gray-100 rounded text-sm uppercase">
                                            {{ str($subscription->planPrice->plan->name)->limit(15) }}
                                        </span>
                                    @endforeach
                                </div>                                
                            @else
                                --                             
                            @endif
                        </x-table.td>
                    @endmodule

                    <x-table.td :status="$account->status" class="text-right"/>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>