<x-form.drawer id="announcement-update" class="max-w-screen-lg p-5" wire:close="close()">
@if ($announcement)
    @if ($announcement->exists) 
        <x-slot:heading title="announcement.heading.update"></x-slot:heading>
        <x-slot:buttons delete>
            <x-button.submit sm/>
            <x-button icon="copy" label="common.label.duplicate" sm wire:click="duplicate()"/>
        </x-slot:buttons>
    @else
        <x-slot:heading title="announcement.heading.create"></x-slot:heading>
    @endif

    <div class="-m-4">
        <x-form.group cols="2">
            <x-form.text label="common.label.title" wire:model.defer="announcement.name"/>
            <x-form.slug label="common.label.slug" wire:model.defer="announcement.slug"/>
            <x-form.date time label="common.label.start-date" wire:model.defer="announcement.start_at"/>
            <x-form.date time label="common.label.end-date" wire:model.defer="announcement.end_at"/>
            <x-form.color full label="common.label.bg-color" wire:model.defer="announcement.bg_color"/>
            <x-form.color full label="common.label.text-color" wire:model.defer="announcement.text_color"/>
        </x-form.group>

        <x-form.group>
            <x-form.text label="common.label.href" wire:model.defer="announcement.href"/>
            <x-form.editor label="common.label.content" wire:model.defer="announcement.content"/>
        </x-form.group>

        <x-form.group heading="SEO">
            <x-form.seo wire:model.defer="inputs.seo"/>
        </x-form.group>
    </div>
@endif
</x-form.drawer>