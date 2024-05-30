<div class="group">
    <button type="button" x-tooltip.raw="Bold" x-on:click="commands().toggleBold()">
        <x-icon name="bold"/>
    </button>
    
    <button type="button" x-tooltip.raw="Italic" x-on:click="commands().toggleItalic()">
        <x-icon name="italic"/>
    </button>
    
    <button type="button" x-tooltip.raw="Underline" x-on:click="commands().toggleUnderline()">
        <x-icon name="underline"/>
    </button>
    
    <button type="button" x-tooltip.raw="Strikethrough" x-on:click="commands().toggleStrike()">
        <x-icon name="strikethrough"/>
    </button>
    
    <button type="button" x-tooltip.raw="Subscript" x-on:click="commands().toggleSubscript()">
        <x-icon name="subscript"/>
    </button>
    
    <button type="button" x-tooltip.raw="Superscript" x-on:click="commands().toggleSuperscript()">
        <x-icon name="superscript"/>
    </button>

    <x-editor.dropdown icon="font" tooltip="Font Size">
        <div class="flex flex-col divide-y">
            <div class="flex items-center justify-center gap-2 p-1">
                <template x-for="size in ['xs', 'sm', 'md', 'lg', 'xl']" hidden>
                    <div
                        x-text="size"
                        x-on:click="commands().setFontSize(size); close()"
                        class="p-1 font-bold text-center cursor-pointer">
                    </div>
                </template>
            </div>

            <div
                x-data="{ size: null }"
                class="p-3 flex flex-col gap-3">
                <x-form.number x-model="size" label="Font Size" postfix="px"/>
                <x-button color="green" sm outlined block
                    label="Set Font Size"
                    x-on:click="commands().setFontSize(`${size}px`); size = null; close()"/>
            </div>
        </div>
    </x-editor.dropdown>

    <x-editor.dropdown icon="align-left" tooltip="Text Align">
        <div class="flex items-center gap-2 p-2">
            <button type="button" x-tooltip.raw="Align Left" x-on:click="commands().setTextAlign('left'); close()">
                <x-icon name="align-left"/>
            </button>

            <button type="button" x-tooltip.raw="Align Center" x-on:click="commands().setTextAlign('center'); close()">
                <x-icon name="align-center"/>
            </button>

            <button type="button" x-tooltip.raw="Align Right" x-on:click="commands().setTextAlign('right'); close()">
                <x-icon name="align-right"/>
            </button>

            <button type="button" x-tooltip.raw="Justify" x-on:click="commands().setTextAlign('justify'); close()">
                <x-icon name="align-justify"/>
            </button>
        </div>
    </x-editor.dropdown>

    <x-editor.dropdown icon="droplet" tooltip="Text Color">
        <div class="grid grid-cols-11 w-max p-1">
            <template x-for="color in color().all()" hidden>
                <div
                    x-on:click="commands().setColor(color); close()"
                    x-bind:style="{ backgroundColor: color }"
                    class="w-5 h-5 hover:border-2 hover:border-gray-400">
                </div>
            </template>

            <div x-on:click="commands().unsetColor(); close()"
                class="w-5 h-5 border hover:border-2 hover:border-gray-400 flex">
                <x-icon name="xmark" class="text-red-500 m-auto"/>
            </div>
        </div>
    </x-editor.dropdown>

    <x-editor.dropdown icon="highlighter" tooltip="Highlight">
        <div class="grid grid-cols-11 w-max p-1">
            <template x-for="color in color().all()" hidden>
                <div
                    x-on:click="commands().setHighlight(color); close()"
                    x-bind:style="{ backgroundColor: color }"
                    class="w-5 h-5 hover:border-2 hover:border-gray-400">
                </div>
            </template>

            <div x-on:click="commands().unsetHighlight(); close()"
                class="w-5 h-5 border hover:border-2 hover:border-gray-400 flex">
                <x-icon name="xmark" class="text-red-500 m-auto"/>
            </div>
        </div>
    </x-editor.dropdown>
</div>    
