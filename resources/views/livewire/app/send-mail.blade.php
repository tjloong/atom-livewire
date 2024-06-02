<x-form.drawer class="max-w-screen-md">
@if ($inputs)
    <x-slot:heading title="app.label.send-email"></x-slot:heading>

    <x-slot:buttons>
        <x-button action="submit" label="Send" icon="paper-plane" sm/>
    </x-slot:buttons>

    <x-group cols="2">
        <x-form.select.email wire:model.defer="inputs.from.email" label="app.label.sender-email"/>
        <x-form.text wire:model.defer="inputs.from.name" label="app.label.sender-name"/>
        <x-form.text wire:model.defer="inputs.reply_to" label="app.label.reply-to"/>
    </x-group>

    <x-group>
        <x-form.select.email wire:model="inputs.to" :options="$options" label="app.label.to" multiple/>
        <x-form.select.email wire:model="inputs.cc" :options="$options" label="app.label.cc" multiple/>
        <x-form.select.email wire:model="inputs.bcc" label="app.label.bcc" multiple/>
        <x-form.text wire:model.defer="inputs.subject" label="app.label.subject"/>
        <x-form.textarea wire:model.defer="inputs.body" label="app.label.body" rows="10"/>

        <x-form.field label="app.label.attachment">
            <x-box.flat>
                <div
                    x-cloak
                    x-data="{ attachments: @entangle('inputs.attachments') }"
                    class="flex flex-col">
                    <template x-for="(item, i) in attachments">
                        <div class="flex items-center gap-3 py-2 px-4 border-b">
                            <x-icon name="paperclip"/> 
                            <div x-text="item.name" class="grow font-medium"></div>
                            <div x-on:click="attachments.splice(i, 1)" class="shrink-0 text-red-500 cursor-pointer">
                                <x-icon name="remove"/>
                            </div>
                        </div>
                    </template>

                    <div class="py-2 px-4" x-data>
                        <input type="file" x-ref="file" wire:model="uploads" class="hidden" multiple>
                        <x-link label="app.label.attach" icon="paperclip" class="flex items-center justify-center"
                            x-on:click="$refs.file.click()"/>
                    </div>
                </div>
            </x-box.flat>
        </x-form.field>
    </x-group>
@endif
</x-form.drawer>