<x-drawer
    x-wire-on:sendmail="(data) => open()?.then(() => $wire.load(data))"
    x-on:close="$wire.cleanup()">
    <x-slot:heading title="app.label.send-email"></x-slot:heading>
    
    @if ($email)
        <x-slot:buttons>
            <x-button action="send" color="green" wire:loading/>
        </x-slot:buttons>

        <x-fieldset inputs>
            <x-input type="email" wire:model.defer="email.from.email" label="app.label.sender-email"/>
            <x-input wire:model.defer="email.from.name" label="app.label.sender-name"/>
            <x-input type="email" wire:model.defer="email.reply_to" label="app.label.reply-to"/>
            <x-email wire:model.defer="email.to" label="app.label.to" :options="get($email, 'email_options')" multiple/>
            <x-email wire:model.defer="email.cc" label="app.label.cc" :options="get($email, 'email_options')" multiple/>
            <x-email wire:model.defer="email.bcc" label="app.label.bcc" multiple/>
            <x-input wire:model.defer="email.subject" label="app.label.subject"/>
            <x-textarea wire:model.defer="email.body" label="app.label.body" rows="10"/>

            <x-field label="app.label.attachment" block>
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach (get($email, 'attachments') as $i => $attachment)
                        <div
                            wire:key="{{ get($attachment, 'id') }}"
                            class="h-10 max-w-72 border border-gray-300 rounded-md bg-white shadow-sm flex items-center gap-3 px-3">
                            <div class="shrink-0 text-gray-400">
                                <x-icon name="attachment"/>
                            </div>

                            <div class="grow truncate">
                                {!! get($attachment, 'filename') !!}
                            </div>

                            <div
                                wire:click="detach({{ $i }})"
                                class="shrink-0 cursor-pointer text-red-500 flex items-center justify-center">
                                <x-icon name="remove"/>
                            </div>
                        </div>
                    @endforeach

                    <div
                        x-data
                        x-on:click="$refs.file.click()"
                        x-tooltip.raw="{{ tr('app.label.attach') }}"
                        class="cursor-pointer h-10 w-10 rounded-md border-2 border-dashed border-gray-500 flex items-center justify-center">
                        <x-icon name="attachment"/>
                        <input type="file" x-ref="file" wire:model="uploads" class="hidden" multiple>
                    </div>
                </div>
            </x-field>
        </x-fieldset>
    @else
        <div class="p-5">
            <x-skeleton/>
        </div>
    @endif
</x-drawer>