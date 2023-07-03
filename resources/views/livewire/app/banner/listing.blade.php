<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Banners">
        <x-button icon="add"
            label="New Banner" 
            :href="route('app.banner.create')"
        />
    </x-page-header>

    <x-table wire:sorted="sort" :data="$this->table">
        <x-slot:header>
            <x-table.searchbar :total="$this->paginator->total()"/>
            <x-table.toolbar>
                @if ($count = count($checkboxes))
                    <x-table.checkboxes :count="$count"/>
                    <x-button.delete :label="'Delete ('.$count.')'" inverted
                        title="Delete Banners"
                        message="Are you sure to DELETE the selected banners?"
                    />
                @else
                    <x-form.select wire:model="filters.status" :options="collect(['active', 'upcoming', 'ended', 'inactive'])->map(fn($val) => [
                        'value' => $val,
                        'label' => str($val)->headline(),
                    ])" :label="false" placeholder="All Status"/>
                @endif
            </x-table.toolbar>
        </x-slot:header>
    </x-table>
</div>