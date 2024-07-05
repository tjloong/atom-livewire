<x-dialog
    x-wire-on:share="(data) => open()?.then(() => $wire.load(data))"
    x-on:close="$wire.cleanup()"
    class="max-w-screen-md">
    <x-slot:heading title="app.label.share" icon="share"></x-slot:heading>

    @if ($share)
        <div class="p-5 flex flex-col gap-5">
            <x-checkbox wire:model="share.is_enabled" label="app.label.enable-sharing"/>

            @if ($share->is_enabled)
                <div
                    x-data="{ copied: false }"
                    x-init="$watch('copied', () => {
                        if (!copied) return
                        $el.querySelector('input').focus()
                        $el.querySelector('input').select()
                        setTimeout(() => copied = false, 300)
                    })"
                    class="relative flex flex-col gap-2">
                    <x-input :value="$share->url" label="app.label.share-link" readonly>
                        <x-slot:button label="app.label.copy" icon="copy"
                            x-on:click="$clipboard({{ Js::from($share->url )}}).finally(() => copied = true)">
                        </x-slot:button>
                    </x-input>

                    <div x-show="copied" x-transition class="absolute -top-1 right-2 bg-black/50 py-1 px-2 rounded-md flex items-center gap-2 text-xs">
                        <x-icon name="check" class="text-green-500"/>
                        <div class="text-white">{{ tr('app.label.copied') }}</div>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-anchor label="Regenerate" icon="refresh" class="text-sm" wire:click="regenerate"/>
                        <x-anchor label="Preview" icon="eye" class="text-sm" href="{{ $share->url }}" target="_blank"/>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <div class="md:w-1/2">
                        <x-input type="number" wire:model.lazy="share.valid_for"
                            suffix="day(s)"
                            placeholder="app.label.valid-for">
                        </x-input>
                    </div>

                    @if ($share->expired_at)
                        <div class="text-sm font-medium text-gray-500">
                            {!! tr('app.label.shared-link-will-expired-on', ['date' => $share->expired_at->pretty()]) !!}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        @if ($this->enabledMethods)
            <x-slot:foot>
                <div class="flex flex-col gap-2">
                    <x-label label="app.label.or-share-to"/>

                    <div class="flex items-center gap-3">
                        @foreach ($this->enabledMethods as $method)
                            @if ($method === 'whatsapp')
                                <x-button action="whatsapp"
                                    target="_blank"
                                    href="https://wa.me?text={{ $share->url }}">
                                </x-button>
                            @endif

                            @if ($method === 'telegram')
                                <x-button action="telegram"
                                    target="_blank"
                                    href="https://telegram.me/share/url?url={{ $share->url }}">
                                </x-button>
                            @endif

                            @if ($method === 'email')
                                <x-button action="send" label="app.label.email"
                                    x-on:click="close()"
                                    wire:click="$emit('sendmail', {{ Js::from(['share_id' => $share->id]) }})">
                                </x-button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </x-slot:foot>
        @endif
    @else
        <div class="p-5">
            <x-skeleton/>
        </div>
    @endif
</x-dialog>
