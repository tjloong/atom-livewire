<div class="group">
    <x-form.editor.dropdown icon="table" tooltip="Table">
        <div x-data="{
            mode: null,
            exists: false,
            init () {
                this.$watch('show', (show) => show && this.start())
            },
            start () {
                editor().chain().focus()
                this.exists = editor().isActive('table')
            },
            back () {
                this.mode = null
            },
        }">
            <div 
                x-data="{ rows: null, cols: null }"
                x-show="!exists" 
                class="flex flex-col gap-2 p-3 w-max">
                <div class="grid grid-cols-6 gap-1">
                    @foreach ([1,2,3,4,5,6] as $row)
                        @foreach ([1,2,3,4,5,6] as $col)
                            <div
                                x-on:mouseover="rows = @js($row); cols = @js($col)"
                                x-on:click="commands().insertTable({ rows, cols }); close()"
                                x-bind:class="rows >= @js($row) && cols >= @js($col)
                                    ? 'bg-blue-500 border-blue-300'
                                    : 'border-gray-400'"
                                class="w-5 h-5 border rounded"></div>
                        @endforeach
                    @endforeach
                </div>

                <div x-show="rows && cols" class="flex items-center justify-center gap-2">
                    <span x-text="rows"></span><span>x</span><span x-text="cols"></span>
                </div>
            </div>

            <div x-show="exists && !mode" class="flex items-center flex-wrap p-1 w-max">
                <button type="button" x-tooltip="Add Column" x-on:click="mode = 'column'">
                    <x-icon name="up-down"/>
                </button>

                <button type="button" x-tooltip="Add Row" x-on:click="mode = 'row'">
                    <x-icon name="left-right"/>
                </button>

                <button type="button" x-tooltip="Merge Cells" x-on:click="commands().mergeCells(); close()">
                    <x-icon name="object-group"/>
                </button>

                <button type="button" x-tooltip="Split Cell" x-on:click="commands().splitCell(); close()">
                    <x-icon name="arrows-left-right-to-line"/>
                </button>

                <button type="button" x-tooltip="Delete Table" x-on:click="commands().deleteTable(); close()">
                    <x-icon name="trash"/>
                </button>
            </div>

            <div x-show="mode === 'column'" class="flex flex-col divide-y w-max text-sm">
                <div x-on:click="back()" class="font-semibold flex items-center gap-3 p-3 cursor-pointer">
                    <x-icon name="arrow-left"/> Add Column
                </div>
                <div class="cursor-pointer p-3" x-on:click="commands().addColumnBefore(); back()">
                    Add column before
                </div>
                <div class="cursor-pointer p-3" x-on:click="commands().addColumnAfter(); back()">
                    Add column after
                </div>
            </div>

            <div x-show="mode === 'row'" class="flex flex-col divide-y w-max text-sm">
                <div x-on:click="back()" class="font-semibold flex items-center gap-3 p-3 cursor-pointer">
                    <x-icon name="arrow-left"/> Add Row
                </div>
                <div class="cursor-pointer p-3" x-on:click="commands().addRowBefore(); back()">
                    Add row before
                </div>
                <div class="cursor-pointer p-3" x-on:click="commands().addRowAfter(); back()">
                    Add row after
                </div>
            </div>
        </div>
    </x-form.editor.dropdown>
</div>