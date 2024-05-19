<div
    x-data="{
        show: false,
        drop (files) {
            this.show = false
            this.$dispatch('dropped', files)
        },
    }"
    x-on:dragover.prevent="show = true"
    x-on:dragenter.prevent="show = true"
    x-on:dragleave.prevent="show = false"
    x-on:dragend.prevent="show = false"
    x-on:drop.prevent="drop($event.dataTransfer.files)"
    {{ $attributes->merge(['class' => 'font-medium text-gray-500'])->only('class') }}>
    <div x-show="show" class="absolute inset-0 bg-white border-2 border-green-500 rounded-lg border-dashed text-green-500 text-lg font-medium flex flex-col items-center justify-center gap-3 md:flex-row">
        <x-icon name="upload" size="24"/> {{ tr('app.label.drop-to-upload') }}
    </div>

    <div class="flex flex-col">
        {{ tr('app.label.or-drop-to-upload', ['max' => format($attributes->get('max'))->filesize('MB')]) }}
        <input type="text" class="transparent w-full" placeholder="{{ tr('app.label.paste-to-upload') }}"
            x-on:click.stop
            x-on:input="$event.target.value = ''"
            x-on:paste.stop="() => {
                let items = $event.clipboardData.items
                let files = Array.from(items)
                    .filter(item => (item.kind === 'file'))
                    .map(item => (item.getAsFile()))

                if (files.length) $dispatch('pasted', files)
            }">
    </div>
</div>
