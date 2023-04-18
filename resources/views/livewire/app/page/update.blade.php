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

    @if ($com = lw('app.page.'.$this->slug))
        @livewire($com, compact('page'))
    @else
        <x-form>
            <x-form.group>
                <x-form.field label="Page Name" :value="$page->name"/>
                <x-form.text wire:model.defer="page.title" label="Page Title"/>
                <x-form.text wire:model.defer="page.slug" label="Page Slug" prefix="/"/>
                <x-form.richtext wire:model.defer="page.content" label="Page Content"/>
            </x-form.group>
        </x-form>    
    @endif
</div>