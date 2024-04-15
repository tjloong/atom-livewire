@php
    $multiple = $attributes->get('multiple', false);
    $enabledUrl = $attributes->get('url') ?? $attributes->get('enable-url') ?? false;
    $enabledLibrary = $attributes->get('library') ?? $attributes->get('enable-library') ?? false;
@endphp

<x-form.field {{ $attributes }}>
    <div class="relative border rounded-lg flex flex-col divide-y overflow-hidden" {{ $attributes->wire('sorted') }}>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            <x-form.file.listing {{ $attributes }}/>
        @endif

        <div
            wire:ignore
            x-cloak
            x-data="{
                tab: 'upload',
                value: @entangle($attributes->wire('model')),
                multiple: @js($multiple),
                input (files) {
                    const value = files.map(val => (val.id))
                    
                    if (this.multiple) {
                        value.forEach(val => {
                            const index = this.value.indexOf(val)
                            if (index === -1) this.value.push(val)
                        })
                    }
                    else if (value.length) this.value = value[0]
                    else this.value = null
                },
            }"
            x-on:files-created="input($event.detail)"
            x-on:files-selected="input($event.detail)"
            x-on:files-uploaded="input($event.detail)"
            class="w-full bg-slate-100 flex flex-col">
            <template x-if="tab === 'upload'">
                <x-form.file.uploader class="p-4" {{ $attributes }}/>
            </template>

            @if ($enabledUrl)
                <template x-if="tab === 'url'">
                    <div class="p-4">
                        <x-form.file.url {{ $attributes }}/>
                    </div>
                </template>
            @endif

            @if ($enabledUrl || $enabledLibrary)
                <div class="p-2 flex flex-wrap items-center gap-1 border-t">
                    <div class="grow flex items-center gap-1">
                        <div
                            x-on:click="tab = 'upload'"
                            x-bind:class="tab === 'upload' ? 'font-medium text-gray-600 bg-gray-200' : 'cursor-pointer text-gray-400 hover:bg-gray-200'"
                            class="py-1.5 px-3 text-sm uppercase rounded-md">
                            {{ tr('app.label.upload') }}
                        </div>

                        @if ($enabledUrl)
                            <div
                                x-on:click="tab = 'url'"
                                x-bind:class="tab === 'url' ? 'font-medium text-gray-600 bg-gray-200' : 'cursor-pointer text-gray-400 hover:bg-gray-200'"
                                class="py-1.5 px-3 text-sm uppercase rounded-md">
                                {{ tr('app.label.get-from-url') }}
                            </div>
                        @endif
                    </div>

                    @if ($enabledLibrary)
                        <div class="shrink-0">
                            <div
                                x-on:click="Livewire.emit('showFilesLibrary', {
                                    accept: {{ Js::from($attributes->get('accept')) }},
                                    multiple: {{ Js::from($attributes->get('multiple')) }},
                                })"
                                class="py-1.5 px-3 text-sm text-gray-500 font-medium uppercase rounded-md cursor-pointer hover:bg-gray-200">
                                {{ tr('app.label.browse-library') }}
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-form.field>