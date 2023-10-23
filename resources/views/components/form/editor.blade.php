<x-form.field {{ $attributes }}>
    <div wire:ignore
        x-cloak
        x-data="setupEditor($wire.entangle('{{ $attributes->wire('model')->value() }}'))"
        x-init="() => init($refs.editor)"
        x-bind:class="focus && 'ring-1 ring-theme'"
        class="editor bg-white border border-gray-300 rounded-lg flex flex-col divide-y divide-gray-300"
        {{ $attributes->whereDoesntStartWith('wire:model') }}>
        <div class="flex items-center flex-wrap p-2 rounded-md">
            <button type="button" class="flex p-2" 
                x-show="canUndo"
                x-tooltip="Undo" 
                x-on:click="undo()">
                <x-icon name="rotate-left" class="m-auto"/>
            </button>
            
            <button type="button" class="flex p-2" 
                x-show="canRedo"
                x-tooltip="Redo" 
                x-on:click="redo()">
                <x-icon name="rotate-right" class="m-auto"/>
            </button>

            <x-dropdown>
                <x-slot:anchor>
                    <button type="button" class="flex p-2">
                        <x-icon name="heading" class="m-auto"/>
                    </button>
                </x-slot:anchor>

                <x-dropdown.item label="Heading 1" class="text-2xl font-bold"
                    x-on:click="toggleHeading({ level: 1 })"/>
                <x-dropdown.item label="Heading 2" class="text-xl font-bold"
                    x-on:click="toggleHeading({ level: 2 })"/>
                <x-dropdown.item label="Heading 3" class="text-lg font-bold"
                    x-on:click="toggleHeading({ level: 3 })"/>
                <x-dropdown.item label="Paragraph" class="text-base"
                    x-on:click="setParagraph"/>
            </x-dropdown>

            <button type="button" class="flex p-2" x-on:click="toggleBold()">
                <x-icon name="bold" class="m-auto"/>
            </button>

            <button type="button" class="flex p-2" x-on:click="toggleItalic()">
                <x-icon name="italic" class="m-auto"/>
            </button>

            <button type="button" class="flex p-2" x-on:click="toggleStrike()">
                <x-icon name="strikethrough" class="m-auto"/>
            </button>

            <button type="button" class="flex p-2" x-on:click="toggleBlockquote()">
                <x-icon name="quote-left" class="m-auto"/>
            </button>

            <button type="button" class="flex p-2" x-on:click="toggleBulletList()">
                <x-icon name="list-ul" class="m-auto"/>
            </button>

            <button type="button" class="flex p-2" x-on:click="toggleOrderedList()">
                <x-icon name="list-ol" class="m-auto"/>
            </button>

            <button type="button" class="flex p-2" x-on:click="sinkListItem('listItem')">
                <x-icon name="indent" class="m-auto"/>
            </button>

            <button type="button" class="flex p-2" x-on:click="liftListItem('listItem')">
                <x-icon name="outdent" class="m-auto"/>
            </button>
        </div>

        <div x-ref="editor"></div>
    </div>
</x-form.field>