@props([
    'uid' => $attributes->get('uid', 'shareable'),
    'header' => $attributes->get('header', 'Share Document'),
    'shareable' => $attributes->get('shareable'),
])

<x-modal 
    :uid="$uid" 
    :header="$header" 
    icon="share" 
    class="max-w-screen-md"
>
    <div
        x-data="{
            loading: false,
            clipboard: null,
            shareable: @js($shareable),
            open () {
                if (this.shareable) return this.enableClipboard()

                this.loading = true
                this.$wire.call('createShareable')
                    .then(res => this.shareable = res)
                    .then(() => this.loading = false)
                    .finally(() => this.$nextTick(() => this.enableClipboard()))
            },
            regenerate () {
                this.loading = true
                this.$wire.call('regenerateShareable', this.shareable.uuid)
                    .then(res => this.shareable = res)
                    .then(() => this.loading = false)
                    .finally(() => this.$nextTick(() => this.enableClipboard()))
            },
            update (data) {
                this.loading = true
                this.$wire.call('updateShareable', { ...this.shareable, ...data })
                    .then(res => this.shareable = res)
                    .then(() => this.loading = false)
                    .finally(() => this.$nextTick(() => this.enableClipboard()))
            },
            enableClipboard () {
                this.clipboard = new ClipboardJS('#shareable-btn')
                this.clipboard.on('success', (e) => this.$dispatch('toast', { message: @js(__('Copied to clipboard')) }))
            },
        }"
        x-on:{{ $uid }}-open.window="open"
        wire:ignore
    >
        <div class="p-6 grid gap-4">
            <template x-if="loading">
                <div class="flex items-center justify-center py-6 text-theme">
                    <svg class="animate-spin"
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none" 
                        viewBox="0 0 24 24"
                        style="width: 45px; height: 45px"
                    >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </template>

            <template x-if="!loading && shareable">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <x-form.text 
                            id="shareable-url"
                            label="Share Link"
                            x-bind:value="shareable.url"
                            readonly
                        >
                            <x-slot:button icon="copy"
                                label="Copy"
                                id="shareable-btn"
                                data-clipboard-target="#shareable-url"
                            ></x-slot:button>
                        </x-form.text>

                        <div>
                            <a x-on:click="regenerate" class="text-sm inline-flex items-center gap-2">
                                <x-icon name="refresh" size="12"/> {{ __('Regenerate') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <x-form.field label="Valid For">
                            <div class="flex items-center gap-2">
                                <x-form.number
                                    x-bind:value="shareable.valid_for"
                                    x-on:input="shareable.valid_for = $event.target.value"
                                    postfix="day(s)"
                                />

                                <div class="shrink-0">
                                    <x-button icon="check"
                                        x-on:click="update" 
                                    />
                                </div>
                            </div>
                        </x-form.field>

                        <div x-show="shareable.expired_at" class="text-sm font-medium text-gray-500">
                            {{ __('Share link will expired on') }} <span x-text="formatDate(shareable.expired_at)"></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="bg-gray-200 grow" style="height: 2px;"></div>
                        <div class="shrink-0 text-sm text-gray-500 font-medium">{{ __('OR SHARE TO') }}</div>
                        <div class="bg-gray-200 grow" style="height: 2px;"></div>
                    </div>

                    <div class="flex items-center justify-center gap-3">
                        @foreach ([
                            ['name' => 'whatsapp', 'url' => 'https://wa.me?text='],
                            ['name' => 'telegram', 'url' => 'https://telegram.me/share/url?url='],
                        ] as $item)
                            <a 
                                x-bind:href="@js(data_get($item, 'url'))+shareable.url"
                                target="_blank"
                                class="py-2 px-4 rounded-lg flex items-center gap-2 {{ [
                                    'whatsapp' => 'bg-green-600 text-white',
                                    'telegram' => 'bg-blue-600 text-white',
                                ][data_get($item, 'name')] }}"
                            >
                                <x-icon :name="data_get($item, 'name')" size="20" class="m-auto"/>
                                {{ str()->headline(data_get($item, 'name')) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </template>
        </div>
    </div>
</x-modal>
