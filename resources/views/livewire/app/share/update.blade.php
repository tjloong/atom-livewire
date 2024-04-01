<x-modal>
@if ($share)
    <x-slot:heading title="Share" icon="share"></x-slot:heading>

    <div class="flex flex-col divide-y">
        <div
            x-cloak 
            x-data="{
                copied: false,
                copy () {
                    $el.querySelector('input[type=text]').focus()
                    $el.querySelector('input[type=text]').select()

                    navigator.clipboard.writeText({{ Js::from($share->url) }}).then(() => {
                        this.copied = true
                        setTimeout(() => this.copied = false, 800)
                    })
                },
            }"
            class="p-5 flex flex-col gap-4">
            <x-form.checkbox wire:model="share.is_enabled" label="Enable Sharing"/>
    
            @if ($share->is_enabled)
                <x-form.field label="Share Link">
                    <div class="relative flex flex-col gap-2">
                        <x-form.text :value="$share->url" icon="link" readonly>
                            <x-slot:button label="Copy" icon="copy" x-on:click="copy()"></x-slot:button>
                        </x-form.text>

                        <div x-show="copied" x-transition class="absolute top-10 right-0 bg-black/50 py-1 px-2 rounded-md flex items-center gap-2 text-xs">
                            <x-icon name="check" class="text-green-500"/>
                            <div class="text-white">{{ tr('app.label.copied') }}</div>
                        </div>
            
                        <div class="flex items-center gap-4">
                            <x-link label="Regenerate" icon="refresh" size="sm" wire:click="regenerate"/>
                            <x-link label="Preview" icon="eye" size="sm" :href="$share->url" target="_blank"/>
                        </div>
                    </div>
                </x-form.field>
    
                <div class="flex flex-col gap-2">
                    <x-form.number wire:model.debounce.400ms="share.valid_for" postfix="day(s)" class="w-1/2"/>
                    @if ($dt = $share->expired_at)
                        <div class="text-sm font-medium text-gray-500">
                            {{ tr('app.label.shared-link-will-expired-on', ['date' => format($dt)]) }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="p-5 bg-slate-100 rounded-b-lg flex flex-col gap-2">
            <div class="text-sm font-medium text-gray-400 uppercase">
                {{ tr('app.label.or-share-to') }}
            </div>

            <div class="flex items-center gap-3">
                @foreach ([
                    ['name' => 'whatsapp', 'url' => 'https://wa.me?text='],
                    ['name' => 'telegram', 'url' => 'https://telegram.me/share/url?url='],
                ] as $item)
                    <a href="{{ get($item, 'url').$share->url }}" target="_blank" 
                        class="py-2 px-4 rounded-lg flex items-center gap-2 {{ [
                            'whatsapp' => 'bg-green-600 text-white',
                            'telegram' => 'bg-blue-600 text-white',
                        ][get($item, 'name')] }}">
                        <x-icon :name="'brands '.get($item, 'name')"/>
                        {{ str()->headline(get($item, 'name')) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
</x-modal>