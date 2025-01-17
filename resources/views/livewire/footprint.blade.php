<x-drawer
    title="app.label.footprint"
    x-wire-on:footprint="(data) => open()?.then(() => $wire.load(data))"
    x-on:close="$wire.cleanup()">
    @if ($footprint)
        <div class="flex flex-col divide-y">
            @foreach ($footprint as $item)
                <div class="p-4 flex flex-col gap-2 hover:bg-slate-50">
                    <div class="text-gray-500 font-medium">
                        {{ get($item, 'timestamp') }}
                    </div>
                    <div>
                        {!! get($item, 'description') !!}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-5">
            <x-skeleton/>
        </div>
    @endif
</x-drawer>
