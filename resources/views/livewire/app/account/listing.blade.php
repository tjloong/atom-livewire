<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title"/>

    <x-table :total="$this->accounts->total()" :links="$this->accounts->links()" export>
        <x-slot:head>
            <x-table.th label="Name" sort="name"/>
            <x-table.th label="Contact"/>
            @module('plans') <x-table.th label="Plans" class="text-right"/> @endmodule
            <x-table.th width="100"/>
            <x-table.th label="Join Date" sort="created_at" class="text-right"/>
        </x-slot:head>

        <x-slot:body>
            @foreach ($this->accounts as $account)
                <x-table.tr>
                    <x-table.td :label="$account->name" :href="route('app.account.update', [$account->id])"/>
                    <x-table.td :label="$account->email"/>

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
                    <x-table.td :date="$account->created_at" class="text-right"/>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>