<div class="group">
    <x-editor.dropdown icon="link" tooltip="Link">
        <div
            x-data="{
                href: null,
                blank: false,
                exists: false,
                get target () {
                    return this.blank ? '_blank' : '_self'
                },
                init () {
                    this.$watch('show', (show) => show && this.start())
                },
                start () {
                    editor().chain().focus()
                    this.href = editor().getAttributes('link').href
                    this.blank = editor().getAttributes('link').target === '_blank'
                    this.exists = editor().isActive('link')
                },
                save () {
                    editor().chain().focus().extendMarkRange('link').setLink({
                        href: this.href,
                        target: this.target,
                    }).run()
                    close()
                },
                remove () {
                    this.href = null
                    this.blank = false
                    editor().chain().focus().extendMarkRange('link').unsetLink().run()
                    close()
                },
            }"
            class="w-80 flex flex-col divide-y">
            <div class="p-3 flex flex-col gap-3">
                <x-form.text label="Link URL" x-ref="href" x-model="href"/>
                <x-form.checkbox label="Open in new tab" x-model="blank"/>
            </div>

            <div class="p-3 flex items-center gap-2">
                <x-button color="green" icon="check" outlined sm
                    label="Save"
                    x-on:click="save()"/>

                <x-button color="red" icon="xmark" outlined sm
                    label="Remove"
                    x-show="exists"
                    x-on:click="remove()"/>
            </div>
        </div>
    </x-editor.dropdown>
    
    <button type="button" x-tooltip.raw="Blockquote" x-on:click="commands().toggleBlockquote()">
        <x-icon name="quote-left"/>
    </button>

    <button type="button" x-tooltip.raw="Horizontal Rule" x-on:click="commands().setHorizontalRule()">
        <x-icon name="minus"/>
    </button>
</div>