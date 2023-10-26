<div class="group">
    <x-form.editor.dropdown icon="image" tooltip="Image">
        <div
            x-data="{
                url: null,
                alt: null,
                exists: false,
                init () {
                    this.$watch('show', (show) => show && this.start())
                },
                start () {
                    editor().chain().focus()
                    this.url = editor().getAttributes('image').src
                    this.alt = editor().getAttributes('image').alt
                    this.exists = editor().isActive('image')
                },
                save () {
                    if (!this.url) return
                    commands().setImage({ src: this.url, alt: this.alt })
                    close()
                },
            }">
            <div x-show="exists" class="flex flex-col divide-y w-max p-1">
                <div class="flex items-center gap-2">
                    <button type="button" x-tooltip="Align Image Left" 
                        x-on:click="commands().updateAttributes('image', { align: 'left', float: null })">
                        <x-icon name="align-left"/>
                    </button>

                    <button type="button" x-tooltip="Align Image Center" 
                        x-on:click="commands().updateAttributes('image', { align: 'center', float: null })">
                        <x-icon name="align-center"/>
                    </button>

                    <button type="button" x-tooltip="Align Image Right" 
                        x-on:click="commands().updateAttributes('image', { align: 'right', float: null })">
                        <x-icon name="align-right"/>
                    </button>

                    <button type="button" x-tooltip="Float Left" 
                        x-on:click="commands().updateAttributes('image', { float: 'left', align: null })">
                        <x-icon name="indent"/>
                    </button>

                    <button type="button" x-tooltip="Float Right" 
                        x-on:click="commands().updateAttributes('image', { float: 'right', align: null })">
                        <x-icon name="outdent"/>
                    </button>
                </div>

                <div class="flex items-center">
                    @foreach (['30%', '50%', '80%', '100%'] as $size)
                        <button type="button" class="font-medium text-sm"
                            x-on:click="commands().updateAttributes('image', { width: @js($size) })">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>

                <div class="flex">
                    <button type="button" x-tooltip="Remove Image" x-on:click="commands().deleteSelection(); close()">
                        <x-icon name="trash"/>
                    </button>
                </div>
            </div>

            <div x-show="!exists" class="w-80">
                <x-form.group>
                    <x-form.text label="Image URL" x-model="url"/>
                    <x-form.text label="Alt Text" x-model="alt"/>
                </x-form.group>
    
                <x-form.group class="p-3">
                    <div class="flex items-center gap-2">
                        <x-button icon="check" color="green" outlined sm block
                            label="Save"
                            x-on:click="save()"/>

                        <x-button icon="search" sm block
                            label="Browse"/>
                    </div>
                </x-form.group>
            </div>
        </div>
    </x-form.editor.dropdown>

    <x-form.editor.dropdown icon="brands youtube" tooltip="Youtube Video">
        <div
            x-data="{
                url: null,
                width: 640,
                height: 480,
                save () {
                    if (!this.url) return

                    commands().setYoutubeVideo({
                        src: this.url,
                        width: this.width,
                        height: this.height,
                    })
                    
                    close()
                }
            }" 
            class="w-80">
            <x-form.group>
                <x-form.text label="Youtube URL" x-model="url"/>
                <x-form.number label="Width" x-model="width"/>
                <x-form.number label="Height" x-model="height"/>
            </x-form.group>

            <x-form.group class="p-3">
                <x-button color="green" icon="add" outlined sm
                    label="Add Youtube Video"
                    x-on:click="save()"/>
            </x-form.group>
        </div>
    </x-form.editor.dropdown>
</div>