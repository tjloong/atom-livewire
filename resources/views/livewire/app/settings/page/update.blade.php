<div>
@if (
    $page
    && ($com = 'app.settings.page.'.$this->slug)
    && has_component($com)
)
    @livewire($com, compact('page'), key('update-'.$page->id))
@else
    <x-form.drawer id="page-update" class="max-w-screen-lg p-5">
    @if ($page)
        <x-slot:heading 
            title="{!! $page->name !!}"
            :status="count(config('atom.locales')) > 1 ? ['blue' => $page->locale] : null"></x-slot:heading>

        <div class="-m-4">
            <x-form.group cols="2">
                <x-form.text label="atom::page.label.title"
                    wire:model.defer="page.title"/>

                <x-form.text label="atom::page.label.slug"
                    wire:model.defer="page.slug"
                    prefix="/"/>
            </x-form.group>

            <x-form.group>
                <x-form.editor label="atom::page.label.content"
                    wire:model="page.content"/>
            </x-form.group>
        </div>
    @endif
    </x-form.drawer>
@endif
</div>