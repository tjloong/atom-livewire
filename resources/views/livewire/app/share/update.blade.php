<x-modal id="share-update" icon="share" header="Share">
@if ($share)
    <div class="flex flex-col divide-y">
        <div x-data="{
            init () {
                const cb = new ClipboardJS('#share-copy')
                cb.on('success', (e) => this.$dispatch('toast', { message: @js(__('Copied to clipboard')) }))
            },
        }" class="p-5 flex flex-col gap-4">
            <x-form.checkbox wire:model="share.is_enabled" label="Enable Sharing"/>
    
            @if ($share->is_enabled)
                <x-form.field label="Share Link">
                    <div class="flex flex-col gap-2">
                        <x-form.text :value="$share->url" prefix="icon:link" id="share-copy-url" readonly>
                            <x-slot:button label="Copy" icon="copy" id="share-copy" data-clipboard-target="#share-copy-url"></x-slot:button>
                        </x-form.text>
            
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
                            {{ __('Share link will expired on :date', ['date' => format_date($dt)]) }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="p-5 bg-slate-100 rounded-b-lg flex flex-col gap-2">
            <div class="text-sm font-medium text-gray-400">
                {{ __('OR SHARE TO') }}
            </div>

            <div class="flex items-center gap-3">
                @foreach ([
                    ['name' => 'whatsapp', 'url' => 'https://wa.me?text='],
                    ['name' => 'telegram', 'url' => 'https://telegram.me/share/url?url='],
                ] as $item)
                    <a href="{{ data_get($item, 'url').$share->url }}" target="_blank" 
                        class="py-2 px-4 rounded-lg flex items-center gap-2 {{ [
                            'whatsapp' => 'bg-green-600 text-white',
                            'telegram' => 'bg-blue-600 text-white',
                        ][data_get($item, 'name')] }}">
                        <x-icon :name="'brands '.data_get($item, 'name')"/>
                        {{ str()->headline(data_get($item, 'name')) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
</x-modal>