<div class="max-w-screen-xl mx-auto">
    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-page-header title="Site Settings"/>
            <x-sidenav>
                @foreach ($tabs['general'] as $item)
                    <x-sidenav item href="{{ route('site-settings', [$item]) }}">{{ str()->headline($item) }}</x-sidenav>
                @endforeach

                <x-sidenav item label>System</x-sidenav>
                @foreach ($tabs['system'] as $item)
                    <x-sidenav item href="{{ route('site-settings', [$item]) }}">{{ str()->headline($item) }}</x-sidenav>
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            <div class="grid gap-6">
                <div class="text-lg font-semibold">{{ str()->headline($tab) }}</div>
                @livewire($this->component_name, key($tab))
            </div>
        </div>
    </div>
</div>
