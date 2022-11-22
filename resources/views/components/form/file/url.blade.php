@props([
    'label' => collect([
        $attributes->get('web-image', true) ? 'Image' : null,
        $attributes->get('youtube', true) ? 'Youtube' : null,
    ])->filter()->join('/').' URL',
    'multiple' => $attributes->get('multiple', false),
    'uid' => $attributes->get('uid', 'file-url'),
])

<div 
    x-data="{
        text: null,
        urls: [],
        wire: @js($attributes->wire('model')->value()),
        loading: false,
        multiple: @js($multiple),
        split () {
            if (empty(this.text)) this.urls = []
            else {
                const urls = this.multiple ? this.text.split(`\n`) : [this.text]
    
                this.loading = true
                this.$wire
                    .loadFileUrls(urls, {
                        image: @js($attributes->get('web-image', true)),
                        youtube: @js($attributes->get('youtube', false)),
                    })
                    .then(res => this.urls = res)
                    .finally(() => this.loading = false)
            }
        },
        submit () {
            this.$wire
                .addFileUrls(this.urls.map(url => (url.url)))
                .then(res => {
                    const value = this.multiple ? res : res[0]
                    if (this.wire) this.$wire.set(this.wire, value.map(val => (val.id)))
                    this.$dispatch(@js($uid.'-added'), value)
                })
                .then(() => {
                    this.urls = []
                    this.text = null
                })
        }
    }"
    class="border border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col gap-3"
>
    @if ($multiple)
        <x-form.textarea :label="$label" 
            x-model="text" 
            x-on:input.debounce.300ms="split" 
            caption="Insert multiple URLs by separating lines"
        />
    @else
        <x-form.text :label="$label" 
            x-model="text" 
            x-on:input.debounce.300ms="split"
        />
    @endif

    <x-form.field x-show="urls.length" label="Preview">
        <div x-show="loading" class="flex items-center gap-2">
            <x-spinner size="20" class="text-theme"/>
        </div>

        <div x-show="!loading" class="flex flex-wrap gap-3">
            <template x-for="url in urls">
                <figure class="w-14 h-14 relative rounded-lg overflow-hidden shadow">
                    <img x-bind:src="url.tn" class="w-full h-full object-cover m-auto">
                </figure>
            </template>
        </div>
    </x-form.field>

    <div x-show="urls.length">
        <x-button color="gray" size="sm" label="Add" x-on:click="submit"/>
    </div>
</div>