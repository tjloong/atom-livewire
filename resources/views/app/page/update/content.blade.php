<form wire:submit.prevent="submit">
    <x-box>
        <div class="p-5">
            <x-input.field>
                <x-slot name="label">Page Name</x-slot>
                {{ $page->name }}
            </x-input.field>

            <x-input.text wire:model.defer="page.title">
                Page Title
            </x-input.text>

            <x-input.slug wire:model.defer="page.slug" prefix="/" required>
                Page Slug
            </x-input.slug>

            <x-input.richtext wire:model.defer="page.content">
                Page Content
            </x-input.richtext>
        </div>

        <x-slot name="buttons">    
            <x-button type="submit" color="green" icon="check">
                Save Page
            </x-button>
        </x-slot>
    </x-box>
</form>
