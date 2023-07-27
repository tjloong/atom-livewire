<x-page-overlay id="page-update">
@if ($page)
    <div class="max-w-screen-xl mx-auto">
        <x-page-header back>
            <x-slot:title>
                <div class="flex items-center gap-2">
                    <div class="text-2xl font-bold">{{ $page->name }}</div>
    
                    @if (count(config('atom.locales')) > 1)
                        <span class="bg-blue-100 text-blue-800 px-3 rounded-full uppercase font-semibold">
                            {{ $page->locale }}
                        </span>
                    @endif
                </div>
            </x-slot:title>
        </x-page-header>
    
        @if (
            ($com = 'app.settings.page.'.$this->slug)
            && has_component($com)
        )
            @livewire($com, compact('page'), key(uniqid()))
        @else
            @livewire('app.settings.page.form', compact('page'), key(uniqid()))
        @endif
    </div>
@endif    
</x-page-overlay>