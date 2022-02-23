<form wire:submit.prevent="submit" class="max-w-lg">
    <x-box>
        <div class="p-5">
            <x-input.text wire:model.defer="settings.seo_title" caption="Recommended title length is 50 ~ 60 characters">
                Meta Title
            </x-input.text>
    
            <x-input.textarea wire:model.defer="settings.seo_description" caption="Recommended description length is 155 ~ 160 characters">
                Meta Description
            </x-input.textarea>
    
            <x-input.text wire:model.defer="settings.seo_image">
                Meta Image URL
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>