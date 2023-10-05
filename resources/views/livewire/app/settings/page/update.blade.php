<div>
@if (
    $page
    && ($com = 'app.settings.page.'.$this->slug)
    && has_component($com)
)
    @livewire($com, compact('page'), key('update-'.$page->id))
@else
    <x-form.drawer id="page-update">
    @if ($page)
        <x-slot:heading 
            title="{!! $page->name !!}"
            :status="count(config('atom.locales')) > 1 ? ['blue' => $page->locale] : null"></x-slot:heading>

        <div class="-m-4">
            <x-form.group>
                <x-form.field label="atom::page.label.name"
                    :value="$page->name"/>

                <x-form.text label="atom::page.label.title"
                    wire:model.defer="page.title"/>

                <x-form.text label="atom::page.label.slug"
                    wire:model.defer="page.slug"
                    prefix="/"/>

                <x-form.richtext label="atom::page.label.content"
                    wire:model.defer="page.content"/>
            </x-form.group>
        </div>
    @endif
    </x-form.drawer>
@endif
</div>