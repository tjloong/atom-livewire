@php
$label = $attributes->get('label', 'app.label.share');
$icon = $attributes->get('icon', 'share');
$entity = $attributes->get('entity');
$model = get_class($entity);
@endphp

<div x-data="{
    show: false,
    share: null,
    loading: false,
    endpoint: {{Js::from(route('__share'))}},

    open () {
        this.show = true
        this.fetch()
    },

    close () {
        this.show = false
    },

    fetch (payload = {}) {
        this.loading = true
        ajax(this.endpoint).post({
            id: {{Js::from($entity->id)}},
            model: {{Js::from($model)}},
            ...payload,
        }).then(res => this.share = { ...res }).then(() => this.loading = false)
    },
}" x-on:click.stop="open()" class="inline-block">
@if ($slot->isNotEmpty())
    {{ $slot }}
@else
    <x-button :icon="$icon" :label="$label" x-on:click.stop="open()"/>
@endif

<template x-teleport="body">
    <div
        x-show="show"
        x-init="$watch('show', show => show && $layering.zindex())"
        x-transition.opacity.duration.300
        class="fixed inset-0">
        <div class="absolute inset-0 bg-black/80" x-on:click.stop="close()"></div>
        <div class="absolute max-w-screen-sm w-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <div class="bg-white rounded-lg border shadow-lg flex flex-col divide-y">
                <div class="p-4 flex items-center gap-3 text-lg font-semibold">
                    <x-icon name="share" class="text-gray-400"/> {{ tr('app.label.share') }}
                </div>

                <x-box.loading.placeholder x-show="loading && !share"/>
                <x-box.loading x-show="loading && share"/>

                <template x-if="share">
                    <div class="p-4 flex flex-col gap-4">
                        <x-form.checkbox x-model="share.is_enabled" x-on:input="$nextTick(() => fetch({ share: {
                            id: share.id,
                            is_enabled: share.is_enabled,
                        }}))" label="app.label.enable-sharing"/>

                        <template x-if="share.is_enabled">
                            <div class="flex flex-col gap-4">
                                <x-form.field label="app.label.share-link">
                                    <div x-data="{ copied: false }" class="relative flex flex-col gap-2">
                                        <x-form.text x-ref="input" x-bind:value="share.url" icon="link" readonly>
                                            <x-slot:button label="Copy" icon="copy" x-on:click="() => {
                                                $refs.input.focus()
                                                $refs.input.select()
                                                $clipboard(share.url)
                                                .then(() => copied = true)
                                                .then(() => setTimeout(() => copied = false, 500))
                                            }"></x-slot:button>
                                        </x-form.text>

                                        <div x-show="copied" x-transition class="absolute top-10 right-0 bg-black/50 py-1 px-2 rounded-md flex items-center gap-2 text-xs">
                                            <x-icon name="check" class="text-green-500"/>
                                            <div class="text-white">{{ tr('app.label.copied') }}</div>
                                        </div>

                                        <div class="flex items-center gap-4">
                                            <x-anchor label="Regenerate" icon="refresh" size="sm" x-on:click="fetch({ regen: true })"/>
                                            <x-anchor label="Preview" icon="eye" size="sm" x-bind:href="share.url" target="_blank"/>
                                        </div>
                                    </div>
                                </x-form.field>

                                <div class="flex flex-col gap-2">
                                    <x-form.number 
                                        x-bind:value="share.valid_for"
                                        x-on:input.debounce.500="fetch({ share: {
                                            id: share.id,
                                            valid_for: $event.target.value,
                                        }})" 
                                        suffix="day(s)"
                                        class="w-1/2"
                                        placeholder="app.label.valid-for"/>
                                    <div x-show="share.notes" x-text="share.notes" class="text-sm font-medium text-gray-500"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="share && share.is_enabled">
                    <div class="p-4 bg-slate-100 rounded-b-lg flex flex-col gap-2">
                        <label class="form-label">{{ tr('app.label.or-share-to') }}</label>
            
                        <div class="flex items-center gap-3">
                            <template x-for="item in [
                                { name: 'whatsapp', url: 'https://wa.me?text='+share.url },
                                { name: 'telegram', url: 'https://telegram.me/share/url?url='+share.url },
                            ]">
                                <a target="_blank"
                                    x-bind:href="item.url"
                                    x-bind:class="{
                                        'bg-green-500 text-white': item.name === 'whatsapp',
                                        'bg-blue-600 text-white': item.name === 'telegram',
                                    }"
                                    class="py-2 px-4 rounded-lg flex items-center gap-2">
                                    <x-icon x-show="item.name === 'whatsapp'" name="brands whatsapp"/>
                                    <x-icon x-show="item.name === 'telegram'" name="brands telegram"/>
                                    <span x-text="item.name" class="capitalize"></span>
                                </a>
                            </template>
                        </div>
                    </div>            
                </template>
            </div>
        </div>
    </div>
</template>
</div>