<div class="p-1">
    <div class="bg-slate-100 rounded-md flex items-center justify-end">
        <button type="button" 
            x-tooltip="Undo" 
            x-show="can().undo()" 
            x-on:click="commands().undo()">
            <x-icon name="rotate-left"/>
        </button>
    
        <button type="button" 
            x-tooltip="Redo" 
            x-show="can().redo()" 
            x-on:click="commands().redo()">
            <x-icon name="rotate-right"/>
        </button>

        <button type="button"
            x-tooltip="Remove Formatting"
            x-on:click="
                commands().unsetAllMarks();
                commands().clearNodes();
            ">
            <x-icon name="text-slash"/>
        </button>
    </div>
</div>
