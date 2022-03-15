<div class="grid gap-6 md:grid-cols-12">
    @if ($this->tabs->count() > 1)
        <div class="md:col-span-3">
            <x-sidenav>
                <div class="grid gap-4">
                    @foreach ($this->tabs as $item)
                        @isset($item['group'])
                            <x-sidenav :group="$item['group']">
                                @foreach ($item['tabs'] as $child)
                                    <x-sidenav item href="{{ route('account', [$child['value']]) }}">{{ $child['label'] }}</x-sidenav>
                                @endforeach
                            </x-sidenav>
                        @else
                            <x-sidenav item href="{{ route('account', [$item['value']]) }}">{{ $item['label'] }}</x-sidenav>
                        @endif
                    @endforeach
                </div>
            </x-sidenav>
        </div>
    @endif

    <div class="{{ $this->tabs->count() > 1 ? 'md:col-span-9' : 'md:col-span-12' }}">
        @if ($component = get_livewire_component($tab, 'account'))
            @livewire($component, compact('user'), key($tab))
        @endif
    </div>
</div>
