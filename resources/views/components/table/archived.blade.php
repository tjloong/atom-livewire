@if ($count = $attributes->get('count') ?? (clone $this->query)->onlyArchived()->count())
    <div class="bg-gray-100 rounded-full font-medium text-sm py-1 px-3 flex items-center gap-2">
        <div class="shrink-0 text-gray-400">
            <x-icon name="box-archive"/>
        </div>

        <div class="grow flex items-center gap-3">
            @if ($this->showArchived)
                {{ __('atom::table.archived.showing', ['count' => $count]) }}
                
                <x-link label="atom::table.archived.restore"
                    wire:click="restoreArchived"
                    wire:key="table-restore-archived"/>

                <x-link label="atom::table.archived.cancel"
                    wire:click="$set('showArchived', false)"
                    wire:key="table-cancel-show-archived"/>
            @else
                {{ __('atom::table.archived.count', ['count' => $count]) }}
                <x-link label="atom::table.archived.show" 
                    x-on:click="
                        $wire.set('showArchived', true);
                        $wire.set('showTrashed', false)
                    "
                    wire:key="table-show-archived"/>
            @endif
        </div>
    </div>
@endif