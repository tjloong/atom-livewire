<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$page->name" back/>

    <form wire:submit.prevent="save">
        <x-box>
            <div class="p-5">
                <x-input.field>
                    <x-slot name="label">Page Name</x-slot>
                    {{ $page->name }}
                </x-input.field>
    
                <x-input.text wire:model.defer="page.title">
                    Page Title
                </x-input.text>
    
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
</div>