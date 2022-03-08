<form wire:submit.prevent="submit">
    <x-box>
        <div class="p-5">
            <x-input.field>
                <x-input.checkbox wire:model="settings.whatsapp_bubble">
                    Enable Whatsapp bubble
                </x-input.checkbox>
            </x-input.field>

            <div x-data="{ show: @entangle('settings.whatsapp_bubble') }" x-show="show">
                <x-input.phone wire:model.defer="settings.whatsapp" class="mb-0">
                    Whatsapp Number
                </x-input.phone>
    
                <x-input.textarea wire:model.defer="settings.whatsapp_text">
                    Prefill Text
                </x-input.textarea>
            </div>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>
