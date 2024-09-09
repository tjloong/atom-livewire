<div class="p-1">
    <div class="bg-slate-100 rounded-md flex items-center justify-end">
        <x-editor.button 
            label="app.label.undo"
            icon="rotate-left"
            x-show="can().undo()" 
            x-on:click="commands().undo()">
        </x-editor.button>
    
        <x-editor.button 
            label="app.label.redo"
            icon="rotate-right"
            x-show="can().redo()" 
            x-on:click="commands().redo()">
        </x-editor.button>

        <x-editor.button
            label="app.label.remove-formatting"
            icon="text-slash"
            x-on:click="
                commands().unsetAllMarks();
                commands().clearNodes();
            ">
        </x-editor.button>
    </div>
</div>
