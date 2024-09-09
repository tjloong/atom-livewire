<div class="group">
    <x-editor.dropdown icon="link" tooltip="app.label.link">
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

                    this.$watch('href', href => {
                        if (href) this.save()
                        else this.remove()
                    })

                    this.$watch('blank', blank => {
                        if (this.href) this.save()
                    })
                },

                save () {
                    editor().chain().focus().extendMarkRange('link').setLink({
                        href: this.href,
                        target: this.target,
                    }).run()
                },

                remove () {
                    editor().chain().focus().extendMarkRange('link').unsetLink().run()
                    this.href = null
                    this.blank = false
                },
            }"
            class="w-80 p-3 flex flex-col gap-2">
                <x-input x-model.lazy="href" placeholder="app.label.link-url"/>
                <x-checkbox x-model="blank" label="app.label.open-in-new-tab"/>
            </div>
    </x-editor.dropdown>
    
    <x-editor.button
        label="app.label.blockquote"
        icon="quote-left"
        x-on:click="commands().toggleBlockquote()">
    </x-editor.button>

    <x-editor.button
        label="app.label.horizontal-rule"
        icon="minus"
        x-on:click="commands().setHorizontalRule()">
    </x-editor.button>
</div>