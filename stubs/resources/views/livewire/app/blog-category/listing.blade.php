<div class="max-w-screen-md mx-auto">
    <x-page-header title="Blog Categories" back="{{ route('blog.listing') }}">
        <x-button icon="plus" href="{{ route('blog-category.create') }}">
            New Category
        </x-button>
    </x-page-header>

    <x-table :total="$labels->total()" :links="$labels->links()">
        <x-slot name="head">
            <x-table.head sort="title">Name</x-table.head>
            <x-table.head align="right">Blogs</x-table.head>
        </x-slot>

        <x-slot name="body">
            @foreach ($labels as $label)
                <x-table.row>
                    <x-table.cell>
                        <a href="{{ route('blog-category.update', [$label->id]) }}">
                            {{ $label->name }}
                        </a>
                    </x-table.cell>
                    <x-table.cell class="text-right">
                        {{ $label->blogs_count }} {{ \Illuminate\Support\Str::of('blog')->plural($label->blogs_count) }}
                    </x-table.cell>
                </x-table.row>                
            @endforeach
        </x-slot>
    </x-table>
</div>
