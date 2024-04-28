<div class="group">
    <x-form.editor.dropdown icon="link" tooltip="Link">
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
            class="w-80">
            <x-group>
                <x-form.text label="Link URL" x-ref="href" x-model="href"/>
                <x-form.checkbox label="Open in new tab" x-model="blank"/>
            </x-group>

            <x-group class="p-3">
                <div class="flex items-center gap-2">
                    <x-button color="green" icon="check" outlined sm block
                        label="Save"
                        x-on:click="save()"/>
        
                    <x-button color="red" icon="xmark" outlined sm block
                        label="Remove"
                        x-show="exists"
                        x-on:click="remove()"/>
                </div>
            </x-group>
        </div>
    </x-form.editor.dropdown>
    
    <button type="button" x-tooltip.raw="Blockquote" x-on:click="commands().toggleBlockquote()">
        <x-icon name="quote-left"/>
    </button>

    <button type="button" x-tooltip.raw="Horizontal Rule" x-on:click="commands().setHorizontalRule()">
        <x-icon name="minus"/>
    </button>
</div>