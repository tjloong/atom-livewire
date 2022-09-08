<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Promotions">
        <x-button.create 
            label="New Promotion" 
            :href="route('app.promotion.create')"
        />
    </x-page-header>

    <x-table :total="$this->promotions->total()" :links="$this->promotions->links()">
        <x-slot:toolbar>
            <x-form.select
                wire:model="filters.status"
                :options="data_get($this->options, 'statuses')"
                placeholder="All Status"
            />
        </x-slot:toolbar>

        <x-slot:head>
            <x-table.th sort="name" label="Name"/>
            <x-table.th label="Code"/>
            <x-table.th label="Type"/>
            <x-table.th label="Rate" class="text-right"/>
            <x-table.th/>
            <x-table.th label="Created Date" class="text-right"/>
        </x-slot:head>

        <x-slot:body>
            @foreach ($this->promotions as $promotion)
                <x-table.tr>
                    <x-table.td :href="route('app.promotion.update', [$promotion->id])" :label="$promotion->name"/>
                    <x-table.td :label="$promotion->code"/>
                    <x-table.td :label="str()->headline($promotion->type)"/>
                    <x-table.td :amount="$promotion->rate" class="text-right"/>
                    <x-table.td :status="$promotion->status" class="text-right"/>
                    <x-table.td :date="$promotion->created_at" class="text-right"/>
                </x-table.tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>