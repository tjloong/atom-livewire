@if ($count = $this->getTableTrashedCount())
    <div class="bg-gray-100 rounded-full font-medium text-sm py-1 px-3 flex items-center gap-2">
        <x-icon delete class="text-gray-400"/>

        <div class="grow flex items-center gap-3">
            @if ($this->tableShowTrashed)
                {{ tr('app.label.showing-trashed', ['count' => $count]) }}
                
                <x-anchor label="app.label.clear" wire:key="table-clear-trashed" x-on:click="Atom.confirm({
                    title: tr('app.alert.clear-trashed.title'),
                    message: tr('app.alert.clear-trashed.message'),
                }, 'error').then(() => $wire.emptyTrashedTableRows())"/>

                <x-anchor label="app.label.cancel" wire:key="table-cancel-show-trashed" wire:click="$set('tableShowTrashed', false)"/>
            @else
                {{ tr('app.label.trashed-count', ['count' => $count]) }}

                <x-anchor label="app.label.show" wire:key="table-show-trashed" x-on:click="() => {
                    $wire.set('tableShowTrashed', true);
                    $wire.set('tableShowArchived', false)
                }"/>
            @endif
        </div>
    </div>
@endif