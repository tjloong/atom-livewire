<div class="max-w-screen-lg mx-auto">
    <x-table>
        <x-slot:header>
            <x-table.header label="Plans">
                <x-button :href="route('app.plan.create')" size="sm" label="New Plan"/>
            </x-table.header>

            <x-table.searchbar :total="$this->plans->total()"/>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Plan" sort="name"/>
            <x-table.th label="Trial"/>
            <x-table.th/>
        </x-slot:thead>

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
    </x-table>

    {!! $this->plans->links() !!}
</div>