@if ($count = $attributes->get('count') ?? (clone $this->query)->onlyTrashed()->count())
    <div class="bg-gray-100 rounded-full font-medium text-sm py-1 px-3 flex items-center gap-2">
        <div class="shrink-0 text-gray-400">
            <x-icon name="trash"/>
        </div>

        <div class="grow flex items-center gap-3">
            @if ($this->showTrashed)
                {{ __('atom::common.label.showing-trashed', ['count' => $count]) }}
                
                <x-link label="atom::common.button.clear"
                    wire:key="table-clear-trashed"
                    x-on:click="$dispatch('confirm', {
                        title: '{{ __('atom::common.alert.clear-trashed.title') }}',
                        message: '{{ __('atom::common.alert.clear-trashed.message') }}',
                        type: 'error',
                        onConfirmed: () => $wire.emptyTrashed(),
                    })"/>

                <x-link label="atom::common.button.cancel"
                    wire:click="$set('showTrashed', false)"
                    wire:key="table-cancel-show-trashed"/>
            @else
                {{ __('atom::common.label.trashed-count', ['count' => $count]) }}

                <x-link label="atom::common.button.show" 
                    wire:key="table-show-trashed"
                    x-on:click="
                        $wire.set('showTrashed', true);
                        $wire.set('showArchived', false)
                    "/>
            @endif
        </div>
    </div>
@endif