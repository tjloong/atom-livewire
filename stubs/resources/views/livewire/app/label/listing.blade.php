<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Labels" back="{{ route('label.listing') }}">
        <x-button icon="plus" href="{{ route('label.create', ['type' => $tab]) }}">
            New Label
        </x-button>
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
            @foreach ($types as $key => $value)
                <x-sidenav item name="{{ $key }}">{{ $value }}</x-sidenav>
            @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            <x-table :total="count($labels)">
                <x-slot name="head">
                    <x-table.head sort="title">Name</x-table.head>
                </x-slot>
        
                <x-slot name="body">
                    @foreach ($labels as $label)
                        <x-table.row>
                            <x-table.cell>
                                <a href="{{ route('label.update', [$label['id']]) }}">
                                    {{ $label['name'] }}
                                </a>
                            </x-table.cell>
                        </x-table.row>                
                    @endforeach
                </x-slot>
            </x-table>
        </div>
    </div>
</div>
