<div class="max-w-screen-lg mx-auto">
    <x-table header="Plans" :total="$this->plans->total()" :links="$this->plans->links()">
        <x-slot:header-buttons>
            <x-button :href="route('app.plan.create')" size="sm" label="New Plan"/>
        </x-slot:header-buttons>

        <x-slot:head>
            <x-table.th label="Plan" sort="name"/>
            <x-table.th label="Trial"/>
            <x-table.th/>
        </x-slot:head>

        <x-slot:body>
            @foreach ($this->plans as $plan)
                <x-table.tr>
                    <x-table.td :label="$plan->name" :href="route('app.plan.update', [$plan->id])"/>

                    <x-table.td>
                        @if ($trial = $plan->trial)
                            {{ __(':count '.str()->plural('day', $trial), ['count' => $trial]) }}
                        @else
                            --
                        @endif
                    </x-table.td>

                    <x-table.td :status="$plan->is_active ? 'active' : 'inactive'" class="text-right"/>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>