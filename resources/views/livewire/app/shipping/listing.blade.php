<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Shipping">
        <x-button label="New Shipping Rate" :href="route('app.shipping.create')"/>
    </x-page-header>

    <x-table :data="$this->table">
    </x-table>
</div>