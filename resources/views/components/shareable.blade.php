@props([
    'id' => component_id($attributes, 'shareable'),
])

@if ($this->shareable)
    <div id="{{ $id }}">
        <div x-on:click="$dispatch(@js($id.'-modal-open'))" class="cursor-pointer">
            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                <x-button :label="$attributes->get('label', 'Share')" :icon="$attributes->get('icon', 'share')"/>
            @endif
        </div>

        <x-modal :id="$id.'-modal'" icon="share" :header="$attributes->get('header', 'Share')" :on-bg-close="false">
            <div x-data="{
                init () {
                    const cb = new ClipboardJS(@js('#'.$id.'-copy'))
                    cb.on('success', (e) => this.$dispatch('toast', { message: @js(__('Copied to clipboard')) }))
                },
            }">
                <x-form.group>
                    <x-form.checkbox wire:model="shareable.is_enabled" label="Enable Sharing"/>
                </x-form.group>

                @if (data_get($this->shareable, 'is_enabled'))
                    <x-form.group>
                        <x-form.field label="Share Link">
                            <div class="flex flex-col gap-2">
                                <x-form.text :value="data_get($this->shareable, 'url')" prefix="icon:link" :id="$id.'-url'" readonly>
                                    <x-slot:button label="Copy" icon="copy" :id="$id.'-copy'" data-clipboard-target="{{ '#'.$id.'-url' }}"></x-slot:button>
                                </x-form.text>
                                <div class="flex items-center gap-4">
                                    <x-link label="Regenerate" icon="refresh" size="sm" wire:click="regenerateShareable"/>
                                    <x-link label="Preview" icon="eye" size="sm" :href="data_get($this->shareable, 'url')" target="_blank"/>
                                </div>
                            </div>
                        </x-form.field>
        
                        <x-form.number wire:model.debounce.400ms="shareable.valid_for" postfix="day(s)" class="w-1/2"/>

                        @if ($dt = data_get($this->shareable, 'expired_at'))
                            <div class="text-sm font-medium text-gray-500">
                                {{ __('Share link will expired on :date', [
                                    'date' => format_date($dt),
                                ]) }}
                            </div>
                        @endif
                    </x-form.group>

                    <x-form.group>
                        <div class="flex flex-col gap-3">
                            <div class="text-sm font-medium text-gray-400">
                                {{ __('OR SHARE TO') }}
                            </div>

                            <div class="flex items-center gap-3">
                                @foreach ([
                                    ['name' => 'whatsapp', 'url' => 'https://wa.me?text='],
                                    ['name' => 'telegram', 'url' => 'https://telegram.me/share/url?url='],
                                ] as $item)
                                    <a href="{{ data_get($item, 'url').data_get($this->shareable, 'url') }}" target="_blank" class="py-2 px-4 rounded-lg flex items-center gap-2 {{ [
                                        'whatsapp' => 'bg-green-600 text-white',
                                        'telegram' => 'bg-blue-600 text-white',
                                    ][data_get($item, 'name')] }}">
                                        <x-icon :name="data_get($item, 'name')" size="20" class="m-auto"/>
                                        {{ str()->headline(data_get($item, 'name')) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </x-form.group>
                @endif
            </div>

            <x-slot:foot>
                <x-button color="gray" label="Close" x-on:click="$dispatch('close')"/>
            </x-slot:foot>
        </x-modal>
    </div>
@endif
