<div x-show="checkboxes.length" class="flex flex-wrap items-center gap-2 p-3" wire:key="table-checkbox-actions">
    <div class="rounded-lg text-sm text-gray-500 font-medium bg-gray-100 border border-gray-300 py-0.5 px-3 flex items-center gap-2">
        <div class="shrink-0 text-xs">
            <x-icon name="check-double"/>
        </div>

        <div x-text="`${checkboxes.length} {{ tr('app.label.selected') }}`" class="grow"></div>
    </div>

    <div class="grow flex items-center justify-between gap-3 flex-wrap">
        {{ $slot }}

        <div class="flex items-center gap-2">
            @if ($attributes->get('archive'))
                @if ($this->tableShowArchived)
                    <x-button.restore sm callback="restoreTableRows"/>
                @elseif (!$this->tableShowTrashed)
                    <x-button.archive sm callback="archiveTableRows"/>
                @endif
            @endif
        
            @if ($attributes->get('trash'))
                @if ($this->tableShowTrashed)
                    <x-button.restore sm callback="restoreTableRows"/>
                @elseif (!$this->tableShowArchived)
                    <x-button.trash sm callback="trashTableRows"/>
                @endif
            @endif

            @if ($attributes->get('delete'))
                <x-button.delete sm callback="deleteTableRows"/>
            @endif
        </div>
    </div>
</div>
