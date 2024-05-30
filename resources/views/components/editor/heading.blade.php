<div class="group">
    <x-editor.dropdown icon="heading" tooltip="Heading">
        <div class="flex flex-col items-center justify-center p-2">
            <template x-for="n in [1, 2, 3, 4]" hidden>
                <div
                    x-text="`H${n}`"
                    x-tooltip="`Heading ${n}`"
                    x-on:click="commands().toggleHeading({ level: n }); close()"
                    class="cursor-pointer p-1 font-bold">
                </div>
            </template>

            <div class="cursor-pointer p-1" x-tooltip.raw="Paragraph" x-on:click="commands().setParagraph(); close()">
                <x-icon name="paragraph"/>
            </div>
        </div>
    </x-editor.dropdown>
</div>