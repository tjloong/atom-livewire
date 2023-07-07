<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$this->title">
        <x-button icon="add"
            label="New Article"
            :href="route('app.blog.create')"
        />
    </x-page-header>

    <x-table :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>

            <x-table.toolbar>
                <x-form.select :label="false"
                    wire:model="filters.status"
                    :options="collect(['published', 'draft'])
                        ->map(fn($val) => ['value' => $val, 'label' => str()->title($val)])"
                    placeholder="All Status"
                />
            </x-table.toolbar>
        </x-slot:header>
    </x-table>

    {!! $this->paginator->links() !!}
</div>