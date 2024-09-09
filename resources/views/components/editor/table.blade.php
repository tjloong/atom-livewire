<div class="group">
    <x-editor.dropdown icon="table" tooltip="app.label.table">
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

            <div x-show="exists && !mode" class="flex flex-col divide-y *:text-center *:py-1.5 *:px-3 *:cursor-pointer hover:*:bg-slate-50 first:*:rounded-t-lg last:*:rounded-b-lg">
                <div x-tooltip.raw="{{ tr('app.label.add-column') }}" x-on:click="mode = 'column'">
                    <x-icon name="up-down"/>
                </div>

                <div x-tooltip.raw="{{ tr('app.label.add-row') }}" x-on:click="mode = 'row'">
                    <x-icon name="left-right"/>
                </div>

                <div x-tooltip.raw="{{ tr('app.label.merge-cells') }}" x-on:click="commands().mergeCells(); close()">
                    <x-icon name="object-group"/>
                </div>

                <div x-tooltip.raw="{{ tr('app.label.split-cell') }}" x-on:click="commands().splitCell(); close()">
                    <x-icon name="arrows-left-right-to-line"/>
                </div>

                <div x-tooltip.raw="{{ tr('app.label.delete-table') }}" x-on:click="commands().deleteTable(); close()">
                    <x-icon name="trash"/>
                </div>
            </div>

            <div x-show="mode === 'column'" class="flex flex-col divide-y *:py-1.5 *:px-3 *:cursor-pointer hover:*:bg-slate-50 first:*:rounded-t-lg last:*:rounded-b-lg">
                <div x-on:click="back()" class="font-semibold flex items-center gap-3 p-3 cursor-pointer">
                    <x-icon name="arrow-left"/> {{ tr('app.label.add-column') }}
                </div>
                <div x-on:click="commands().addColumnBefore(); back()">
                    {{ tr('app.label.add-column-before') }}
                </div>
                <div x-on:click="commands().addColumnAfter(); back()">
                    {{ tr('app.label.add-column-after') }}
                </div>
            </div>

            <div x-show="mode === 'row'" class="flex flex-col divide-y *:py-1.5 *:px-3 *:cursor-pointer hover:*:bg-slate-50 first:*:rounded-t-lg last:*:rounded-b-lg">
                <div x-on:click="back()" class="font-semibold flex items-center gap-3 p-3 cursor-pointer">
                    <x-icon name="arrow-left"/> {{ tr('app.label.add-row') }}
                </div>
                <div x-on:click="commands().addRowBefore(); back()">
                    {{ tr('app.label.add-row-before') }}
                </div>
                <div x-on:click="commands().addRowAfter(); back()">
                    {{ tr('app.label.add-row-after') }}
                </div>
            </div>
        </div>
    </x-editor.dropdown>
</div>