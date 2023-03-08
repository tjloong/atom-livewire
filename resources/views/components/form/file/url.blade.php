@props([
    'multiple' => $attributes->get('multiple', false),
    'label' => collect([
        $attributes->get('web-image', true) ? 'Image' : null,
        $attributes->get('youtube', false) ? 'Youtube' : null,
    ])->filter()->join('/').' URL',
])

<div 
    x-data="{
        text: null,
        urls: [],
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
                    this.$dispatch('url', value)
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

    <div x-show="urls.length" class="flex flex-col gap-3">
        <x-form.field label="Preview">
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

        <x-button color="gray" size="sm" label="Add" x-on:click="submit"/>
    </div>
</div>