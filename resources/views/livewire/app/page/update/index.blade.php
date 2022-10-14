<div class="max-w-screen-lg mx-auto">
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

    @if ($com = lw('app.page.update.'.$this->slug) ?? lw('app.page.update.content'))
        @livewire($com, compact('page'))
    @endif
</div>