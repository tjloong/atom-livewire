<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Blogs">
        <x-button label="New Blog" href="{{ route('app.blog.create') }}"/>
    </x-page-header>

    <x-table>
        <x-slot:header>
            <x-table.searchbar :total="$this->blogs->total()"/>

            <x-table.toolbar>
                <x-form.select
                    wire:model="filters.status"
                    :options="collect(['published', 'draft'])
                        ->map(fn($val) => ['value' => $val, 'label' => str()->title($val)])"
                    placeholder="All Status"
                />
            </x-table.toolbar>
        </x-slot:header>

        <x-slot:thead>
            <x-table.th label="Title" sort="title">Title</x-table.th>
            <x-table.th label="Category"/>
            <x-table.th label="Last Updated" sort="updated_at" class="text-right">Updated At</x-table.th>
            <x-table.th/>
        </x-slot:thead>

        @foreach ($this->blogs as $blog)
            <x-table.tr>
                <x-table.td :label="$blog->title" :href="route('app.blog.update', [$blog->id])"/>
                <x-table.td :tags="$blog->labels->pluck('name.'.app()->currentLocale())"/>
                <x-table.td :from-now="$blog->updated_at" class="text-right"/>
                <x-table.td :status="$blog->status" class="text-right"/>
            </x-table.tr>
        @endforeach
    </x-table>

    {!! $this->blogs->links() !!}
</div>