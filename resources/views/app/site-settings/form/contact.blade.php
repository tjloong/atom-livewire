<div class="max-w-lg">
    <form wire:submit.prevent="save">
        <x-box>
            <x-slot name="header">Contact Information</x-slot>

            <div class="p-5">
                <x-input.text wire:model.defer="settings.company">
                    Company
                </x-input.text>
    
                <x-input.text wire:model.defer="settings.phone">
                    Phone
                </x-input.text>
                    
                <x-input.text wire:model.defer="settings.email">
                    Email
                </x-input.text>
    
                <x-input.textarea wire:model.defer="settings.address">
                    Address
                </x-input.textarea>
            </div>
    
            <x-slot name="buttons">
                <x-button type="submit" icon="check" color="green">
                    Save
                </x-button>
            </x-slot>
        </x-box>
    </form>
    
    <form wire:submit.prevent="save">
        <x-box>
            <x-slot name="header">Whatsapp</x-slot>

            <div class="p-5">
                <x-input.phone wire:model.defer="settings.whatsapp" class="mb-0">
                    Number
                </x-input.phone>

                <x-input.textarea wire:model.defer="settings.whatsapp_text">
                    Prefill Text
                </x-input.textarea>

                <x-input.field>
                    <x-input.checkbox wire:model="settings.whatsapp_bubble">
                        Use Bubble
                    </x-input.checkbox>
                </x-input.field>
            </div>
    
            <x-slot name="buttons">
                <x-button type="submit" icon="check" color="green">
                    Save
                </x-button>
            </x-slot>
        </x-box>
    </form>
</div>