<div class="max-w-screen-lg mx-auto">
    <x-page-header title="Site Settings"/>
    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav>
                @foreach ($tabs as $group => $items)
                    <x-sidenav :group="$group">
                        @foreach ($items as $key => $item)
                            <x-sidenav item
                                href="{{ route('site-settings', [is_numeric($key) ? $item : $key]) }}"
                            >
                                {{ str($item)->headline() }}
                            </x-sidenav>
                        @endforeach
                    </x-sidenav>
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            <div class="grid gap-6">
                @if ($component = get_livewire_component($tab, 'app/site-settings'))
                    @livewire($component, key($tab))
                @endif
            </div>
        </div>
    </div>
</div>
