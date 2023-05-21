<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Banners">
        <x-button label="New Banner" :href="route('app.banner.create')"/>
    </x-page-header>

    <x-table wire:sorted="sort" :data="$this->table">
    </x-table>
</div>