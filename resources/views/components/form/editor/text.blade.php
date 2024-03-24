<div class="group">
    <button type="button" x-tooltip="Bold" x-on:click="commands().toggleBold()">
        <x-icon name="bold"/>
    </button>
    
    <button type="button" x-tooltip="Italic" x-on:click="commands().toggleItalic()">
        <x-icon name="italic"/>
    </button>
    
    <button type="button" x-tooltip="Underline" x-on:click="commands().toggleUnderline()">
        <x-icon name="underline"/>
    </button>
    
    <button type="button" x-tooltip="Strikethrough" x-on:click="commands().toggleStrike()">
        <x-icon name="strikethrough"/>
    </button>
    
    <button type="button" x-tooltip="Subscript" x-on:click="commands().toggleSubscript()">
        <x-icon name="subscript"/>
    </button>
    
    <button type="button" x-tooltip="Superscript" x-on:click="commands().toggleSuperscript()">
        <x-icon name="superscript"/>
    </button>

    <x-form.editor.dropdown icon="font" tooltip="Font Size">
        <div class="flex flex-col divide-y">
            <div class="flex items-center justify-center gap-2 p-1">
                @foreach (['xs', 'sm', 'md', 'lg', 'xl'] as $size)
                    <div class="p-1 font-bold text-center cursor-pointer" x-on:click="commands().setFontSize(@js($size))">
                        {{ $size }}
                    </div>
                @endforeach
            </div>

            <div
                x-data="{
                    size: null,
                    save () {
                        commands().setFontSize(`${this.size}px`)
                        this.size = null
                        close()
                    },
                }"
                class="w-52">
                <x-group>
                    <x-form.number x-model="size" label="Font Size" postfix="px"/>
                    <x-button color="green" sm outlined block
                        label="Set Font Size"
                        x-on:click="save()"/>
                </x-group>
            </div>
        </div>
    </x-form.editor.dropdown>

    <x-form.editor.dropdown icon="align-left" tooltip="Text Align">
        <button type="button" x-tooltip="Align Left" x-on:click="commands().setTextAlign('left')">
            <x-icon name="align-left"/>
        </button>

        <button type="button" x-tooltip="Align Center" x-on:click="commands().setTextAlign('center')">
            <x-icon name="align-center"/>
        </button>

        <button type="button" x-tooltip="Align Right" x-on:click="commands().setTextAlign('right')">
            <x-icon name="align-right"/>
        </button>

        <button type="button" x-tooltip="Justify" x-on:click="commands().setTextAlign('justify')">
            <x-icon name="align-justify"/>
        </button>
    </x-form.editor.dropdown>

    <x-form.editor.dropdown icon="droplet" tooltip="Text Color">
        <div class="grid grid-cols-11 w-max p-1">
            @foreach (color()->all() as $color)
                <div x-on:click="commands().setColor(@js($color)); close()"
                    class="w-5 h-5 hover:border-2 hover:border-gray-400"
                    style="background-color: {{ $color }};"></div>
            @endforeach

            <div x-on:click="commands().unsetColor()"
                class="w-5 h-5 border hover:border-2 hover:border-gray-400 flex">
                <x-icon name="xmark" class="text-red-500 m-auto"/>
            </div>
        </div>
    </x-form.editor.dropdown>

    <x-form.editor.dropdown icon="highlighter" tooltip="Highlight">
        <div class="grid grid-cols-11 w-max p-1">
            @foreach (color()->all() as $color)
                <div x-on:click="commands().setHighlight(@js($color)); close()"
                    class="w-5 h-5 hover:border-2 hover:border-gray-400"
                    style="background-color: {{ $color }};"></div>
            @endforeach

            <div x-on:click="commands().unsetHighlight()"
                class="w-5 h-5 border hover:border-2 hover:border-gray-400 flex">
                <x-icon name="xmark" class="text-red-500 m-auto"/>
            </div>
        </div>
    </x-form.editor.dropdown>
</div>    
