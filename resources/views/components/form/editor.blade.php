@php
    $colors = json_decode(file_get_contents(atom_path('resources/json/colors.json')), true);
@endphp

<x-form.field {{ $attributes }}>
    <div wire:ignore
        x-cloak
        x-data="setupEditor($wire.entangle('{{ $attributes->wire('model')->value() }}'))"
        x-init="() => init($refs.editor)"
        x-bind:class="focus && 'ring-1 ring-theme'"
        class="editor bg-white border border-gray-300 rounded-lg flex flex-col divide-y divide-gray-300"
        {{ $attributes->whereDoesntStartWith('wire:model') }}>
        <div class="flex flex-wrap gap-3 p-2">
            <div class="grow flex items-center flex-wrap">
                <div class="relative" 
                    x-data="{ show: false }"
                    x-on:click.away="show = false"
                    x-tooltip="Heading">
                    <button type="button" x-on:click="show = true">
                        <x-icon name="heading"/>
                    </button>
                    <div x-show="show" class="absolute z-10 bg-white border rounded-md p-2">
                        <div class="flex items-center justify-center">
                            <div class="cursor-pointer px-2 font-bold" x-on:click="toggleHeading({ level: 1 })">H1</div>
                            <div class="cursor-pointer px-2 font-bold" x-on:click="toggleHeading({ level: 2 })">H2</div>
                            <div class="cursor-pointer px-2 font-bold" x-on:click="toggleHeading({ level: 3 })">H3</div>
                        </div>
                    </div>
                </div>

                <button type="button" x-tooltip="Paragraph" x-on:click="setParagraph()" x-show="isActive('heading')">
                    <x-icon name="paragraph"/>
                </button>

                <button type="button" x-tooltip="Bold" x-on:click="toggleBold()">
                    <x-icon name="bold"/>
                </button>
    
                <button type="button" x-tooltip="Italic" x-on:click="toggleItalic()">
                    <x-icon name="italic"/>
                </button>
    
                <button type="button" x-tooltip="Strikethrough" x-on:click="toggleStrike()">
                    <x-icon name="strikethrough"/>
                </button>

                <div x-data="{ show: false }" x-on:click.away="show = false" class="relative">
                    <button type="button" x-tooltip="Text Color" x-on:click="show = true">
                        <x-icon name="font"/>
                    </button>

                    <div x-show="show" class="absolute z-10 bg-white border rounded-md p-1 max-h-[250px] overflow-auto w-max">
                        <div class="grid grid-cols-11">
                            @foreach ($colors['full'] as $color)
                                <div x-on:click="setColor(@js($color))"
                                    class="w-5 h-5 hover:border-2 hover:border-gray-400"
                                    style="background-color: {{ $color }};"></div>
                            @endforeach

                            <div x-on:click="unsetColor()"
                                class="w-5 h-5 border hover:border-2 hover:border-gray-400 flex">
                                <x-icon name="xmark" class="text-red-500 m-auto"/>
                            </div>
                        </div>
                    </div>
                </div>
    
                <button type="button" x-tooltip="Blockquote" x-on:click="toggleBlockquote()">
                    <x-icon name="quote-left"/>
                </button>
    
                <button type="button" x-tooltip="Bullet List" x-on:click="toggleBulletList()">
                    <x-icon name="list-ul"/>
                </button>
    
                <button type="button" x-tooltip="Ordered List" x-on:click="toggleOrderedList()">
                    <x-icon name="list-ol"/>
                </button>
    
                <button type="button" x-tooltip="Sink List Item" x-on:click="sinkListItem('listItem')">
                    <x-icon name="indent"/>
                </button>
    
                <button type="button" x-tooltip="Lift List Item" x-on:click="liftListItem('listItem')">
                    <x-icon name="outdent"/>
                </button>
            </div>

            <div class="shrink flex items-center">
                <button type="button" x-show="canUndo" x-tooltip="Undo" x-on:click="undo()">
                    <x-icon name="rotate-left"/>
                </button>

                <button type="button" x-show="canRedo" x-tooltip="Redo" x-on:click="redo()">
                    <x-icon name="rotate-right"/>
                </button>
            </div>
        </div>

        <div x-ref="editor"></div>
    </div>
</x-form.field>