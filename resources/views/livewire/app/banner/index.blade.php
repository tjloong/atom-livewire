<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Banners">
        <x-button icon="add"
            label="New Banner" 
            wire:click="update"
        />
    </x-page-header>

    <x-table wire:sorted="sort">
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
                    <x-form.select.enum :label="false"
                        wire:model="filters.status"
                        enum="banner.status"
                        placeholder="All Status"
                    />
                @endif
            </x-table.toolbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Name" sort="name"/>
            <x-table.th label="Type" sort="type"/>
            <x-table.th label="Start Date" sort="start_at"/>
            <x-table.th label="End Date" sort="end_at"/>
            <x-table.th label="Status"/>
        </x-slot:thead>

        @foreach ($this->paginator->items() as $banner)
            <x-table.tr data-sortable-id="{{ $banner->id }}">
                <x-table.td :label="$banner->name"
                    :image="optional($banner->image)->url"
                    wire:click="update({{ $banner->id }})"/>
                <x-table.td :label="$banner->type"/>
                <x-table.td :date="$banner->start_at"/>
                <x-table.td :date="$banner->end_at"/>
                <x-table.td :status="[$banner->status->color() => $banner->status->value]"/>
            </x-table.td>
        @endforeach
    </x-table>

    {!! $this->paginator->links() !!}

    @livewire('app.banner.update', key(uniqid()))
</div>