@if ($count = $attributes->get('count') ?? (clone $this->query)->onlyTrashed()->count())
    <div class="bg-gray-100 rounded-full font-medium text-sm py-1 px-3 flex items-center gap-2">
        <div class="shrink-0 text-gray-400">
            <x-icon name="trash"/>
        </div>

        <div class="grow flex items-center gap-3">
            @if ($this->showTrashed)
                {{ tr('common.label.showing-trashed', ['count' => $count]) }}
                
                <x-link label="common.label.clear"
                    wire:key="table-clear-trashed"
                    x-on:click="$dispatch('confirm', {
                        title: '{{ tr('common.alert.clear-trashed.title') }}',
                        message: '{{ tr('common.alert.clear-trashed.message') }}',
                        type: 'error',
                        onConfirmed: () => $wire.emptyTrashed(),
                    })"/>

                <x-link label="common.label.cancel"
                    wire:click="$set('showTrashed', false)"
                    wire:key="table-cancel-show-trashed"/>
            @else
                {{ tr('common.label.trashed-count', ['count' => $count]) }}

                <x-link label="common.label.show" 
                    wire:key="table-show-trashed"
                    x-on:click="
                        $wire.set('showTrashed', true);
                        $wire.set('showArchived', false)
                    "/>
            @endif
        </div>
    </div>
@endif