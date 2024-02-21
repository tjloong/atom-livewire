@if ($count = $attributes->get('count') ?? (clone $this->query)->whereNotNull('archived_at')->count())
    <div class="bg-gray-100 rounded-full font-medium text-sm py-1 px-3 flex items-center gap-2">
        <div class="shrink-0 text-gray-400">
            <x-icon name="box-archive"/>
        </div>

        <div class="grow flex items-center gap-3">
            @if ($this->tableShowArchived)
                {{ tr('app.label.showing-archived', ['count' => $count]) }}
                <x-link label="app.label.restore" wire:key="table-restore-archived" wire:click="restoreTableRows"/>
                <x-link label="app.label.cancel" wire:key="table-cancel-show-archived" wire:click="$set('tableShowArchived', false)"/>
            @else
                {{ tr('app.label.archived-count', ['count' => $count]) }}
                <x-link label="app.label.show" wire:key="table-show-archived" x-on:click="() => {
                    Livewire.set('tableShowArchived', true)
                    Livewire.set('tableShowTrashed', false)
                }"/>
            @endif
        </div>
    </div>
@endif