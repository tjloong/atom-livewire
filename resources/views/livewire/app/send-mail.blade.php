<x-form.drawer class="max-w-screen-md">
@if ($inputs)
    <x-slot:heading title="app.label.send-email"></x-slot:heading>

    <x-slot:buttons>
        <x-button.submit sm label="Send" icon="paper-plane"/>
    </x-slot:buttons>

    <x-group cols="2">
        <x-form.email wire:model.defer="inputs.from.email" label="app.label.sender-email"/>
        <x-form.text wire:model.defer="inputs.from.name" label="app.label.sender-name"/>
        <x-form.text wire:model.defer="inputs.reply_to" label="app.label.reply-to"/>
    </x-group>

    <x-group>
        <x-form.email wire:model="inputs.to" :options="$options" label="app.label.to" multiple/>
        <x-form.email wire:model="inputs.cc" :options="$options" label="app.label.cc" multiple/>
        <x-form.email wire:model="inputs.bcc" label="app.label.bcc" multiple/>
        <x-form.text wire:model.defer="inputs.subject" label="app.label.subject"/>
        <x-form.textarea wire:model.defer="inputs.body" label="app.label.body" rows="10"/>

        <x-form.field label="app.label.attachment">
            <x-box.flat>
                <div class="flex flex-col divide-y">
                    @foreach (data_get($inputs, 'attachments') as $attachment)
                        <div class="flex items-center gap-3 py-2 px-4">
                            <x-icon name="paperclip"/> 
                            <div class="grow font-medium">{{ data_get($attachment, 'name') }}</div>
                            <div class="shrink-0 text-red-500 cursor-pointer" wire:click="detach(@js(data_get($attachment, 'id')))">
                                <x-icon name="remove"/>
                            </div>
                        </div>
                    @endforeach

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