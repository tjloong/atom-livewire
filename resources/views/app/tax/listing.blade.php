<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Taxes">
        <x-button.create label="New Tax" href="{{ route('app.tax.create') }}"/>
    </x-page-header>

    <x-table :total="$this->taxes->total()" :links="$this->taxes->links()">
        <x-slot:head>
            <x-table.th sort="name" label="Tax Name"/>
            <x-table.th sort="country" label="Country"/>
            <x-table.th sort="region" label="Region"/>
            <x-table.th class="text-right" label="Rate"/>
            <x-table.th/>
        </x-slot:head>

        <x-slot:body>
        @foreach ($this->taxes as $tax)
            <x-table.tr>
                <x-table.td :href="route('app.tax.update', [$tax->id])" :label="$tax->name"/>
                <x-table.td :label="$tax->country"/>
                <x-table.td :label="$tax->region"/>
                <x-table.td class="text-right" :percentage="$tax->rate"/>
                <x-table.td class="text-right" :status="$tax->is_active ? 'active' : 'inactive'"/>
            </x-table.tr>
        @endforeach
        </x-slot:body>
    </x-table>
</div>
